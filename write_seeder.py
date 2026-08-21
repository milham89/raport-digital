import subprocess

seeder = r"""<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('student_attendance_remarks')->delete();
        DB::table('grades')->delete();
        DB::table('students')->delete();
        DB::table('teacher_assignments')->delete();
        DB::table('classes')->delete();
        DB::table('subjects')->delete();
        DB::table('academic_years')->delete();
        DB::table('users')->delete();

        $a  = DB::table('users')->insertGetId(['name'=>'Administrator','email'=>'admin@raport.sch.id','password'=>Hash::make('admin123'),'nip'=>'196001011980011001','role'=>'admin','is_active'=>true,'created_at'=>now(),'updated_at'=>now()]);
        $k  = DB::table('users')->insertGetId(['name'=>'Drs. Budi Santoso, M.Pd.','email'=>'kepsek@raport.sch.id','password'=>Hash::make('kepsek123'),'nip'=>'196505151990011001','role'=>'principal','is_active'=>true,'created_at'=>now(),'updated_at'=>now()]);
        $g1 = DB::table('users')->insertGetId(['name'=>'Siti Rahayu, S.Pd.','email'=>'guru1@raport.sch.id','password'=>Hash::make('guru123'),'nip'=>'198003012005012001','role'=>'teacher','is_active'=>true,'created_at'=>now(),'updated_at'=>now()]);
        $g2 = DB::table('users')->insertGetId(['name'=>'Ahmad Fauzi, S.Pd.','email'=>'guru2@raport.sch.id','password'=>Hash::make('guru123'),'nip'=>'198507152006011002','role'=>'teacher','is_active'=>true,'created_at'=>now(),'updated_at'=>now()]);
        $w1 = DB::table('users')->insertGetId(['name'=>'Dewi Lestari, S.Pd.','email'=>'wali1@raport.sch.id','password'=>Hash::make('wali123'),'nip'=>'197908102003012003','role'=>'homeroom','is_active'=>true,'created_at'=>now(),'updated_at'=>now()]);

        $yr = DB::table('academic_years')->insertGetId(['year'=>'2025/2026','semester'=>'GANJIL','is_active'=>true,'created_at'=>now(),'updated_at'=>now()]);
        $cl = DB::table('classes')->insertGetId(['name'=>'X IPA 1','grade_level'=>'10','homeroom_teacher_id'=>$w1,'academic_year_id'=>$yr,'created_at'=>now(),'updated_at'=>now()]);

        $mat = DB::table('subjects')->insertGetId(['code'=>'MTK','name'=>'Matematika','kkm'=>75,'created_at'=>now(),'updated_at'=>now()]);
        $ind = DB::table('subjects')->insertGetId(['code'=>'BIN','name'=>'Bahasa Indonesia','kkm'=>75,'created_at'=>now(),'updated_at'=>now()]);
        $ing = DB::table('subjects')->insertGetId(['code'=>'BIG','name'=>'Bahasa Inggris','kkm'=>75,'created_at'=>now(),'updated_at'=>now()]);
        $fis = DB::table('subjects')->insertGetId(['code'=>'FIS','name'=>'Fisika','kkm'=>75,'created_at'=>now(),'updated_at'=>now()]);
        $kim = DB::table('subjects')->insertGetId(['code'=>'KIM','name'=>'Kimia','kkm'=>75,'created_at'=>now(),'updated_at'=>now()]);
        $bio = DB::table('subjects')->insertGetId(['code'=>'BIO','name'=>'Biologi','kkm'=>75,'created_at'=>now(),'updated_at'=>now()]);

        DB::table('teacher_assignments')->insert([
            ['teacher_id'=>$g1,'subject_id'=>$mat,'class_id'=>$cl,'academic_year_id'=>$yr,'created_at'=>now(),'updated_at'=>now()],
            ['teacher_id'=>$g1,'subject_id'=>$fis,'class_id'=>$cl,'academic_year_id'=>$yr,'created_at'=>now(),'updated_at'=>now()],
            ['teacher_id'=>$g2,'subject_id'=>$ind,'class_id'=>$cl,'academic_year_id'=>$yr,'created_at'=>now(),'updated_at'=>now()],
            ['teacher_id'=>$g2,'subject_id'=>$ing,'class_id'=>$cl,'academic_year_id'=>$yr,'created_at'=>now(),'updated_at'=>now()],
            ['teacher_id'=>$g1,'subject_id'=>$kim,'class_id'=>$cl,'academic_year_id'=>$yr,'created_at'=>now(),'updated_at'=>now()],
            ['teacher_id'=>$g2,'subject_id'=>$bio,'class_id'=>$cl,'academic_year_id'=>$yr,'created_at'=>now(),'updated_at'=>now()],
        ]);

        $names = [1=>'Andi Pratama',2=>'Budi Setiawan',3=>'Citra Dewi',4=>'Diana Putri',5=>'Eko Saputra'];
        foreach ($names as $i => $name) {
            DB::table('students')->insert(['nis'=>'242500'.$i,'nisn'=>'009123400'.$i,'name'=>$name,'gender'=>in_array($i,[3,4])?'P':'L','class_id'=>$cl,'created_at'=>now(),'updated_at'=>now()]);
        }
    }
}
"""

with open(r'D:\Apps Web\Aplikasi Raport\deploy\database\seeders\DatabaseSeeder.php', 'w', encoding='utf-8') as f:
    f.write(seeder)

print("SEEDER WRITTEN OK")
