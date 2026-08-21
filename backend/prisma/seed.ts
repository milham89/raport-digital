import { PrismaClient } from '@prisma/client';
import bcrypt from 'bcryptjs';

const prisma = new PrismaClient();

async function main() {
  console.log('Seeding initial data...');

  const passwordHash = await bcrypt.hash('admin123', 10);
  const teacherPasswordHash = await bcrypt.hash('guru123', 10);

  // 1. Users
  const admin = await prisma.user.upsert({
    where: { username: 'admin' },
    update: {},
    create: {
      username: 'admin',
      passwordHash,
      fullName: 'Administrator Utama',
      nip: '199001012020011001',
      role: 'admin',
    },
  });

  const teacher1 = await prisma.user.upsert({
    where: { username: 'guru1' },
    update: {},
    create: {
      username: 'guru1',
      passwordHash: teacherPasswordHash,
      fullName: 'Budi Santoso, S.Pd.',
      nip: '198505122010011002',
      role: 'teacher',
    },
  });

  const homeroom1 = await prisma.user.upsert({
    where: { username: 'wali1' },
    update: {},
    create: {
      username: 'wali1',
      passwordHash: teacherPasswordHash,
      fullName: 'Siti Aminah, M.Pd.',
      nip: '198803152012012003',
      role: 'homeroom',
    },
  });

  const principal = await prisma.user.upsert({
    where: { username: 'kepsek' },
    update: {},
    create: {
      username: 'kepsek',
      passwordHash: teacherPasswordHash,
      fullName: 'Dr. H. Ahmad Dahlan, M.Pd.',
      nip: '197501011998031004',
      role: 'principal',
    },
  });

  // 2. Academic Year
  const academicYear = await prisma.academicYear.upsert({
    where: {
      yearName_semester: {
        yearName: '2024/2025',
        semester: 'ganjil',
      },
    },
    update: { isActive: true },
    create: {
      yearName: '2024/2025',
      semester: 'ganjil',
      isActive: true,
    },
  });

  // 3. Class
  const classX = await prisma.class.upsert({
    where: {
      className_academicYearId: {
        className: 'X IPA 1',
        academicYearId: academicYear.id,
      },
    },
    update: { homeroomTeacherId: homeroom1.id },
    create: {
      className: 'X IPA 1',
      academicYearId: academicYear.id,
      homeroomTeacherId: homeroom1.id,
    },
  });

  // 4. Subjects
  const subjectMath = await prisma.subject.upsert({
    where: { code: 'MAT-X' },
    update: {},
    create: {
      code: 'MAT-X',
      name: 'Matematika',
      kkm: 75.0,
    },
  });

  const subjectIndo = await prisma.subject.upsert({
    where: { code: 'BIN-X' },
    update: {},
    create: {
      code: 'BIN-X',
      name: 'Bahasa Indonesia',
      kkm: 75.0,
    },
  });

  // 5. Students
  await prisma.student.upsert({
    where: { nis: '2024001' },
    update: { classId: classX.id },
    create: {
      nis: '2024001',
      nisn: '0051234567',
      fullName: 'Andi Pratama',
      gender: 'L',
      classId: classX.id,
    },
  });

  await prisma.student.upsert({
    where: { nis: '2024002' },
    update: { classId: classX.id },
    create: {
      nis: '2024002',
      nisn: '0051234568',
      fullName: 'Siti Rahmawati',
      gender: 'P',
      classId: classX.id,
    },
  });

  // 6. Teacher Assignment
  await prisma.teacherAssignment.upsert({
    where: {
      teacherId_subjectId_classId_academicYearId: {
        teacherId: teacher1.id,
        subjectId: subjectMath.id,
        classId: classX.id,
        academicYearId: academicYear.id,
      },
    },
    update: {},
    create: {
      teacherId: teacher1.id,
      subjectId: subjectMath.id,
      classId: classX.id,
      academicYearId: academicYear.id,
    },
  });

  console.log('Seeding finished successfully.');
}

main()
  .catch((e) => {
    console.error(e);
    process.exit(1);
  })
  .finally(async () => {
    await prisma.$disconnect();
  });

