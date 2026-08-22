<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\TeacherAssignment;
use App\Models\Student;
use App\Models\SchoolSetting;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers    = User::count();
        $totalTeachers = User::whereIn('role', ['teacher', 'homeroom'])->count();
        $totalClasses  = SchoolClass::count();
        $totalStudents = Student::count();
        $totalSubjects = Subject::count();
        $activeYear    = AcademicYear::where('is_active', true)->first();

        return view('admin.dashboard', compact('totalUsers', 'totalTeachers', 'totalClasses', 'totalStudents', 'totalSubjects', 'activeYear'));
    }

    public function users() { return view('admin.users', ['users' => User::orderBy('role')->get()]); }
    public function createUser() { return view('admin.user-form', ['user' => null]); }

    public function storeUser(Request $request)
    {
        $request->validate(['name'=>'required','email'=>'required|email|unique:users','password'=>'required|min:6','role'=>'required|in:admin,teacher,homeroom,principal']);
        User::create(['name'=>$request->name,'email'=>$request->email,'password'=>Hash::make($request->password),'nip'=>$request->nip,'role'=>$request->role,'is_active'=>true]);
        return redirect()->route('admin.users')->with('success','User berhasil ditambahkan.');
    }

    public function editUser(User $user) { return view('admin.user-form', compact('user')); }

    public function updateUser(Request $request, User $user)
    {
        $request->validate(['name'=>'required','email'=>'required|email|unique:users,email,'.$user->id,'role'=>'required|in:admin,teacher,homeroom,principal']);
        $data = ['name'=>$request->name,'email'=>$request->email,'nip'=>$request->nip,'role'=>$request->role,'is_active'=>$request->boolean('is_active',true)];
        if ($request->filled('password')) $data['password'] = Hash::make($request->password);
        $user->update($data);
        return redirect()->route('admin.users')->with('success','User berhasil diupdate.');
    }

    public function destroyUser(User $user) { $user->delete(); return back()->with('success','User dihapus.'); }

    public function academicYears() { return view('admin.academic-years', ['years' => AcademicYear::orderByDesc('id')->get()]); }

    public function storeAcademicYear(Request $request)
    {
        $request->validate(['year'=>'required','semester'=>'required|in:GANJIL,GENAP']);
        if ($request->boolean('is_active')) AcademicYear::query()->update(['is_active'=>false]);
        AcademicYear::create(['year'=>$request->year,'semester'=>$request->semester,'is_active'=>$request->boolean('is_active')]);
        return back()->with('success','Tahun ajaran ditambahkan.');
    }

    public function activateYear(AcademicYear $year)
    {
        AcademicYear::query()->update(['is_active'=>false]);
        $year->update(['is_active'=>true]);
        return back()->with('success','Tahun ajaran diaktifkan.');
    }

    public function destroyAcademicYear(AcademicYear $year)
    {
        $year->delete();
        return back()->with('success','Tahun ajaran dihapus.');
    }

    public function classes()
    {
        return view('admin.classes', [
            'classes'   => SchoolClass::with(['homeroomTeacher','academicYear'])->get(),
            'years'     => AcademicYear::all(),
            'teachers'  => User::whereIn('role', ['homeroom', 'teacher'])->get(),
            'homerooms' => User::where('role', 'homeroom')->get()
        ]);
    }

    public function storeClass(Request $request)
    {
        $request->validate(['name'=>'required','grade_level'=>'required','academic_year_id'=>'required|exists:academic_years,id']);
        SchoolClass::create($request->only('name','grade_level','homeroom_teacher_id','academic_year_id'));
        return back()->with('success','Kelas ditambahkan.');
    }

    public function destroyClass(SchoolClass $class) { $class->delete(); return back()->with('success','Kelas dihapus.'); }

    public function subjects() { return view('admin.subjects', ['subjects' => Subject::all()]); }

    public function storeSubject(Request $request)
    {
        $request->validate(['code'=>'required|unique:subjects','name'=>'required','kkm'=>'required|numeric']);
        Subject::create($request->only('code','name','kkm'));
        return back()->with('success','Mata pelajaran ditambahkan.');
    }

    public function destroySubject(Subject $subject) { $subject->delete(); return back()->with('success','Mata pelajaran dihapus.'); }

    public function assignments() { return view('admin.assignments', ['assignments'=>TeacherAssignment::with(['teacher','subject','schoolClass','academicYear'])->get(),'teachers'=>User::where('role','teacher')->get(),'subjects'=>Subject::all(),'classes'=>SchoolClass::with('academicYear')->get(),'years'=>AcademicYear::all()]); }

    public function storeAssignment(Request $request)
    {
        $request->validate(['teacher_id'=>'required','subject_id'=>'required','class_id'=>'required','academic_year_id'=>'required']);
        TeacherAssignment::firstOrCreate($request->only('teacher_id','subject_id','class_id','academic_year_id'));
        return back()->with('success','Penugasan ditambahkan.');
    }

    public function destroyAssignment(TeacherAssignment $assignment) { $assignment->delete(); return back()->with('success','Penugasan dihapus.'); }

    public function students()
    {
        return view('admin.students', [
            'students' => Student::with(['schoolClass','userAccount'])->orderBy('name')->get(),
            'classes'  => SchoolClass::with('academicYear')->get()
        ]);
    }

    public function storeStudent(Request $request)
    {
        $request->validate([
            'nis'      => 'required|unique:students',
            'nisn'     => 'required|unique:students',
            'name'     => 'required|string|max:255',
            'gender'   => 'required|in:L,P',
            'class_id' => 'required|exists:classes,id',
            'password' => 'nullable|min:6'
        ]);

        $student = Student::create($request->only('nis','nisn','name','gender','class_id'));

        // Auto-create login user account for student if password provided
        $password = $request->filled('password') ? $request->password : 'siswa123';
        User::create([
            'name'       => $student->name,
            'email'      => 'siswa.' . $student->nis . '@raport.sch.id',
            'password'   => Hash::make($password),
            'student_id' => $student->id,
            'role'       => 'student',
            'is_active'  => true,
        ]);

        return back()->with('success', 'Siswa ' . $student->name . ' & akun login berhasil dibuat. NIS: ' . $student->nis . ' (Password: ' . $password . ')');
    }

    public function destroyStudent(Student $student)
    {
        // Delete related user account if exists
        User::where('student_id', $student->id)->delete();
        $student->delete();
        return back()->with('success', 'Data siswa berhasil dihapus.');
    }

    public function setStudentPassword(Request $request, Student $student)
    {
        $request->validate(['password' => 'required|min:6']);

        $user = User::where('student_id', $student->id)->first();
        if ($user) {
            $user->update(['password' => Hash::make($request->password)]);
        } else {
            User::create([
                'name'       => $student->name,
                'email'      => 'siswa.' . $student->nis . '@raport.sch.id',
                'password'   => Hash::make($request->password),
                'student_id' => $student->id,
                'role'       => 'student',
                'is_active'  => true,
            ]);
        }

        return back()->with('success', 'Password akun ' . $student->name . ' berhasil diperbarui.');
    }

    public function previewReportCard($student = null)
    {
        $activeYear = AcademicYear::where('is_active', true)->first() ?? AcademicYear::first();
        $studentObj = null;

        if ($student instanceof Student) {
            $studentObj = $student;
        } elseif (is_numeric($student)) {
            $studentObj = Student::with('schoolClass.homeroomTeacher')->find($student);
        }

        if (!$studentObj) {
            $studentObj = Student::with('schoolClass.homeroomTeacher')->first();
        }

        if (!$studentObj) {
            $teacher = (object)['name' => 'Dra. Siti Aminah, M.Pd.', 'nip' => '197503122000032001'];
            $class = (object)[
                'name' => 'X-MIPA-1',
                'homeroomTeacher' => $teacher
            ];
            $studentData = (object)[
                'id' => 0,
                'name' => 'Ahmad Fulan (Contoh Siswa)',
                'nis' => '2425001',
                'nisn' => '0091234001',
                'gender' => 'L',
                'schoolClass' => $class
            ];
            $grades = collect([
                (object)['subject' => (object)['name' => 'Pendidikan Agama dan Budi Pekerti', 'kkm' => 75], 'final_score' => 88, 'letter_grade' => 'A'],
                (object)['subject' => (object)['name' => 'Pendidikan Pancasila dan Kewarganegaraan', 'kkm' => 75], 'final_score' => 85, 'letter_grade' => 'A'],
                (object)['subject' => (object)['name' => 'Bahasa Indonesia', 'kkm' => 75], 'final_score' => 86, 'letter_grade' => 'A'],
                (object)['subject' => (object)['name' => 'Matematika Umum', 'kkm' => 75], 'final_score' => 82, 'letter_grade' => 'B'],
                (object)['subject' => (object)['name' => 'Bahasa Inggris', 'kkm' => 75], 'final_score' => 84, 'letter_grade' => 'B'],
            ]);
            $remark = (object)[
                'sick' => 1,
                'permission' => 0,
                'unexcused' => 0,
                'homeroom_note' => 'Pertahankan prestasi belajarmu dan terus aktif dalam kegiatan sekolah.',
                'homeroom_remark' => 'Pertahankan prestasi belajarmu dan terus aktif dalam kegiatan sekolah.'
            ];
            $student = $studentData;
        } else {
            $student = $studentObj;
            $class = $student->schoolClass;
            $grades = Grade::with(['subject', 'teacher'])
                ->where('student_id', $student->id)
                ->when($activeYear, fn($q) => $q->where('academic_year_id', $activeYear->id))
                ->get();

            if ($grades->isEmpty()) {
                $grades = collect([
                    (object)['subject' => (object)['name' => 'Matematika Umum', 'kkm' => 75], 'final_score' => 85, 'letter_grade' => 'A'],
                    (object)['subject' => (object)['name' => 'Bahasa Indonesia', 'kkm' => 75], 'final_score' => 88, 'letter_grade' => 'A'],
                    (object)['subject' => (object)['name' => 'Bahasa Inggris', 'kkm' => 75], 'final_score' => 80, 'letter_grade' => 'B'],
                ]);
            }

            $remark = StudentAttendanceRemark::where('student_id', $student->id)
                ->when($activeYear, fn($q) => $q->where('academic_year_id', $activeYear->id))
                ->first() ?? (object)[
                    'sick' => 0,
                    'permission' => 0,
                    'unexcused' => 0,
                    'homeroom_note' => 'Pertahankan semangat belajar dan prestasimu.',
                    'homeroom_remark' => 'Pertahankan semangat belajar dan prestasimu.'
                ];
        }

        $setting = SchoolSetting::getSettings();
        return view('report.card', compact('student', 'class', 'activeYear', 'grades', 'remark', 'setting'));
    }

    public function schoolSettings()
    {
        $setting = SchoolSetting::getSettings();
        return view('admin.school-settings', compact('setting'));
    }

    public function updateSchoolSettings(Request $request)
    {
        $request->validate([
            'school_name'    => 'required|string|max:255',
            'school_level'   => 'nullable|string|max:100',
            'school_address' => 'nullable|string|max:255',
            'principal_name' => 'required|string|max:255',
            'principal_nip'  => 'nullable|string|max:50',
            'report_place'   => 'required|string|max:100',
            'report_date'    => 'nullable|string|max:100',
            'header_title'   => 'required|string|max:255',
        ]);

        $setting = SchoolSetting::getSettings();
        $setting->update($request->only([
            'school_name',
            'school_level',
            'school_address',
            'principal_name',
            'principal_nip',
            'report_place',
            'report_date',
            'header_title',
        ]));

        return back()->with('success', 'Pengaturan format raport & profil sekolah berhasil disimpan.');
    }
}
