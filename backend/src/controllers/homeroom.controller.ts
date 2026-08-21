import { Response } from 'express';
import { prisma } from '../config/database';
import { AuthRequest } from '../middlewares/auth.middleware';

export const getMyHomeroomClass = async (req: AuthRequest, res: Response) => {
  try {
    const homeroomTeacherId = req.user?.id;
    const activeYear = await prisma.academicYear.findFirst({ where: { isActive: true } });

    if (!activeYear) {
      return res.status(400).json({ message: 'Tidak ada tahun ajaran aktif.' });
    }

    const classData = await prisma.class.findFirst({
      where: {
        homeroomTeacherId,
        academicYearId: activeYear.id,
      },
      include: {
        students: {
          orderBy: { fullName: 'asc' },
          include: {
            attendances: {
              where: { academicYearId: activeYear.id },
            },
          },
        },
      },
    });

    if (!classData) {
      return res.status(404).json({ message: 'Anda tidak terdaftar sebagai wali kelas di tahun ajaran aktif ini.' });
    }

    return res.json({ class: classData, activeYear });
  } catch (error) {
    return res.status(500).json({ message: 'Gagal mengambil data kelas bimbingan.' });
  }
};

export const saveAttendanceAndRemarks = async (req: AuthRequest, res: Response) => {
  try {
    const { studentId, classId, sickDays, permissionDays, unexcusedDays, extracurricularNotes, homeroomNote } = req.body;

    const activeYear = await prisma.academicYear.findFirst({ where: { isActive: true } });
    if (!activeYear) {
      return res.status(400).json({ message: 'Tidak ada tahun ajaran aktif.' });
    }

    const record = await prisma.studentAttendanceRemark.upsert({
      where: {
        studentId_academicYearId: {
          studentId,
          academicYearId: activeYear.id,
        },
      },
      update: {
        classId,
        sickDays: Number(sickDays) || 0,
        permissionDays: Number(permissionDays) || 0,
        unexcusedDays: Number(unexcusedDays) || 0,
        extracurricularNotes: extracurricularNotes || [],
        homeroomNote: homeroomNote || '',
      },
      create: {
        studentId,
        classId,
        academicYearId: activeYear.id,
        sickDays: Number(sickDays) || 0,
        permissionDays: Number(permissionDays) || 0,
        unexcusedDays: Number(unexcusedDays) || 0,
        extracurricularNotes: extracurricularNotes || [],
        homeroomNote: homeroomNote || '',
        status: 'draft',
      },
    });

    return res.json({ message: 'Absensi dan catatan siswa berhasil disimpan.', record });
  } catch (error) {
    return res.status(500).json({ message: 'Gagal menyimpan absensi dan catatan.' });
  }
};

export const lockClassReport = async (req: AuthRequest, res: Response) => {
  try {
    const { classId } = req.body;
    const activeYear = await prisma.academicYear.findFirst({ where: { isActive: true } });
    if (!activeYear) {
      return res.status(400).json({ message: 'Tidak ada tahun ajaran aktif.' });
    }

    // Update all attendance remarks for this class to finalized
    await prisma.studentAttendanceRemark.updateMany({
      where: {
        classId,
        academicYearId: activeYear.id,
      },
      data: { status: 'finalized' },
    });

    return res.json({ message: 'Raport kelas berhasil dikunci (Finalized) dan menunggu persetujuan Kepala Sekolah.' });
  } catch (error) {
    return res.status(500).json({ message: 'Gagal mengunci nilai raport.' });
  }
};
