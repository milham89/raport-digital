import { Response } from 'express';
import { prisma } from '../config/database';
import { AuthRequest } from '../middlewares/auth.middleware';

export const getPrintableReport = async (req: AuthRequest, res: Response) => {
  try {
    const { studentId } = req.params;
    let { academicYearId } = req.query;

    if (!academicYearId) {
      const activeYear = await prisma.academicYear.findFirst({ where: { isActive: true } });
      if (!activeYear) {
        return res.status(400).json({ message: 'Tidak ada tahun ajaran aktif.' });
      }
      academicYearId = activeYear.id;
    }

    const student = await prisma.student.findUnique({
      where: { id: studentId },
      include: {
        class: {
          include: {
            homeroomTeacher: { select: { id: true, fullName: true, nip: true } },
          },
        },
      },
    });

    if (!student) {
      return res.status(404).json({ message: 'Data siswa tidak ditemukan.' });
    }

    const academicYear = await prisma.academicYear.findUnique({
      where: { id: String(academicYearId) },
    });

    // Get principal user
    const principal = await prisma.user.findFirst({
      where: { role: 'principal', isActive: true },
      select: { fullName: true, nip: true },
    });

    // Get all grades for this student
    const grades = await prisma.grade.findMany({
      where: {
        studentId,
        academicYearId: String(academicYearId),
      },
      include: {
        subject: true,
      },
      orderBy: { subject: { code: 'asc' } },
    });

    // Get attendance & remark
    const attendanceRemark = await prisma.studentAttendanceRemark.findUnique({
      where: {
        studentId_academicYearId: {
          studentId,
          academicYearId: String(academicYearId),
        },
      },
    });

    return res.json({
      schoolInfo: {
        name: 'SMA NEGERI 1 INDONESIA',
        address: 'Jl. Pendidikan No. 123, Kota Jakarta',
        email: 'info@sman1.sch.id',
      },
      student: {
        id: student.id,
        nis: student.nis,
        nisn: student.nisn,
        fullName: student.fullName,
        gender: student.gender,
        className: student.class?.className || '-',
      },
      academicYear: {
        yearName: academicYear?.yearName,
        semester: academicYear?.semester,
      },
      homeroomTeacher: student.class?.homeroomTeacher || { fullName: 'Wali Kelas', nip: '-' },
      principal: principal || { fullName: 'Kepala Sekolah', nip: '-' },
      grades: grades.map((g) => ({
        subjectCode: g.subject.code,
        subjectName: g.subject.name,
        kkm: g.subject.kkm,
        finalGrade: g.finalGrade,
        predicate: g.predicate,
        feedback: g.feedback,
      })),
      attendance: {
        sickDays: attendanceRemark?.sickDays || 0,
        permissionDays: attendanceRemark?.permissionDays || 0,
        unexcusedDays: attendanceRemark?.unexcusedDays || 0,
        extracurricularNotes: attendanceRemark?.extracurricularNotes || [],
        homeroomNote: attendanceRemark?.homeroomNote || '-',
        status: attendanceRemark?.status || 'draft',
      },
    });
  } catch (error) {
    console.error('Error report:', error);
    return res.status(500).json({ message: 'Gagal mengambil data lembar raport.' });
  }
};
