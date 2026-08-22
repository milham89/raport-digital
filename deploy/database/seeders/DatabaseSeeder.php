<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('daily_attendances')) {
            DB::table('daily_attendances')->delete();
        }
        DB::table('student_attendance_remarks')->delete();
        DB::table('grades')->delete();
        DB::table('teacher_assignments')->delete();
        DB::table('users')->delete();
        DB::table('students')->delete();
        DB::table('classes')->delete();
        DB::table('subjects')->delete();
        DB::table('academic_years')->delete();

        // 1. Staff Users
        $a  = DB::table('users')->insertGetId(['name'=>'Administrator','email'=>'admin@raport.sch.id','password'=>Hash::make('admin123'),'nip'=>'196001011980011001','role'=>'admin','is_active'=>true,'created_at'=>now(),'updated_at'=>now()]);
        $k  = DB::table('users')->insertGetId(['name'=>'Drs. Budi Santoso, M.Pd.','email'=>'kepsek@raport.sch.id','password'=>Hash::make('kepsek123'),'nip'=>'196505151990011001','role'=>'principal','is_active'=>true,'created_at'=>now(),'updated_at'=>now()]);
        $g1 = DB::table('users')->insertGetId(['name'=>'Siti Rahayu, S.Pd.','email'=>'guru1@raport.sch.id','password'=>Hash::make('guru123'),'nip'=>'198003012005012001','role'=>'teacher','is_active'=>true,'created_at'=>now(),'updated_at'=>now()]);
        $g2 = DB::table('users')->insertGetId(['name'=>'Ahmad Fauzi, S.Pd.','email'=>'guru2@raport.sch.id','password'=>Hash::make('guru123'),'nip'=>'198507152006011002','role'=>'teacher','is_active'=>true,'created_at'=>now(),'updated_at'=>now()]);
        $w1 = DB::table('users')->insertGetId(['name'=>'Dewi Lestari, S.Pd.','email'=>'wali1@raport.sch.id','password'=>Hash::make('wali123'),'nip'=>'197908102003012003','role'=>'homeroom','is_active'=>true,'created_at'=>now(),'updated_at'=>now()]);

        // 2. Academic Year
        $yr = DB::table('academic_years')->insertGetId(['year'=>'2025/2026','semester'=>'GANJIL','is_active'=>true,'created_at'=>now(),'updated_at'=>now()]);

        // 3. Class
        $cl = DB::table('classes')->insertGetId(['name'=>'X IPA 1','grade_level'=>'10','homeroom_teacher_id'=>$w1,'academic_year_id'=>$yr,'created_at'=>now(),'updated_at'=>now()]);

        // 4. Subjects
        $mat = DB::table('subjects')->insertGetId(['code'=>'MTK','name'=>'Matematika','kkm'=>75,'created_at'=>now(),'updated_at'=>now()]);
        $ind = DB::table('subjects')->insertGetId(['code'=>'BIN','name'=>'Bahasa Indonesia','kkm'=>75,'created_at'=>now(),'updated_at'=>now()]);
        $ing = DB::table('subjects')->insertGetId(['code'=>'BIG','name'=>'Bahasa Inggris','kkm'=>75,'created_at'=>now(),'updated_at'=>now()]);
        $fis = DB::table('subjects')->insertGetId(['code'=>'FIS','name'=>'Fisika','kkm'=>75,'created_at'=>now(),'updated_at'=>now()]);
        $kim = DB::table('subjects')->insertGetId(['code'=>'KIM','name'=>'Kimia','kkm'=>75,'created_at'=>now(),'updated_at'=>now()]);
        $bio = DB::table('subjects')->insertGetId(['code'=>'BIO','name'=>'Biologi','kkm'=>75,'created_at'=>now(),'updated_at'=>now()]);

        // 5. Assignments
        DB::table('teacher_assignments')->insert([
            ['teacher_id'=>$g1,'subject_id'=>$mat,'class_id'=>$cl,'academic_year_id'=>$yr,'created_at'=>now(),'updated_at'=>now()],
            ['teacher_id'=>$g1,'subject_id'=>$fis,'class_id'=>$cl,'academic_year_id'=>$yr,'created_at'=>now(),'updated_at'=>now()],
            ['teacher_id'=>$g2,'subject_id'=>$ind,'class_id'=>$cl,'academic_year_id'=>$yr,'created_at'=>now(),'updated_at'=>now()],
            ['teacher_id'=>$g2,'subject_id'=>$ing,'class_id'=>$cl,'academic_year_id'=>$yr,'created_at'=>now(),'updated_at'=>now()],
            ['teacher_id'=>$g1,'subject_id'=>$kim,'class_id'=>$cl,'academic_year_id'=>$yr,'created_at'=>now(),'updated_at'=>now()],
            ['teacher_id'=>$g2,'subject_id'=>$bio,'class_id'=>$cl,'academic_year_id'=>$yr,'created_at'=>now(),'updated_at'=>now()],
        ]);

        // 6. Students & Student User Accounts (Siswa bisa login!)
        $studentsData = [
            1 => ['name'=>'Andi Pratama',  'nis'=>'2425001', 'nisn'=>'0091234001', 'gender'=>'L'],
            2 => ['name'=>'Budi Setiawan', 'nis'=>'2425002', 'nisn'=>'0091234002', 'gender'=>'L'],
            3 => ['name'=>'Citra Dewi',    'nis'=>'2425003', 'nisn'=>'0091234003', 'gender'=>'P'],
            4 => ['name'=>'Diana Putri',   'nis'=>'2425004', 'nisn'=>'0091234004', 'gender'=>'P'],
            5 => ['name'=>'Eko Saputra',   'nis'=>'2425005', 'nisn'=>'0091234005', 'gender'=>'L'],
        ];

        foreach ($studentsData as $i => $s) {
            $studentId = DB::table('students')->insertGetId([
                'nis'        => $s['nis'],
                'nisn'       => $s['nisn'],
                'name'       => $s['name'],
                'gender'     => $s['gender'],
                'class_id'   => $cl,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // User account for student (login: email or NIS, password: password123 / siswa123)
            DB::table('users')->insert([
                'name'       => $s['name'],
                'email'      => 'siswa' . $i . '@raport.sch.id',
                'password'   => Hash::make('siswa123'),
                'student_id' => $studentId,
                'role'       => 'student',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}

