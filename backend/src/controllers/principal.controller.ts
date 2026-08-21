import { Response } from 'express';
import { prisma } from '../config/database';
import { AuthRequest } from '../middlewares/auth.middleware';

export const getMonitoringOverview = async (req: AuthRequest, res: Response) => {
  try {
    const activeYear = await prisma.academicYear.findFirst({ where: { isActive: true } });
    if (!activeYear) {
      return res.status(400).json({ message: 'Tidak ada tahun ajaran aktif.' });
    }

    const classes = await prisma.class.findMany({
      where: { academicYearId: activeYear.id },
      include: {
        homeroomTeacher: { select: { id: true, fullName: true, nip: true } },
        _count: { select: { students: true } },
        attendances: {
          where: { academicYearId: activeYear.id },
          select: { status: true },
        },
      },
    });

    const overview = classes.map((c) => {
      const totalStudents = c._count.students;
      const finalizedCount = c.attendances.filter((a) => a.status === 'finalized' || a.status === 'approved').length;
      const approvedCount = c.attendances.filter((a) => a.status === 'approved').length;

      let status: 'draft' | 'finalized' | 'approved' = 'draft';
      if (approvedCount > 0 && approvedCount >= totalStudents) {
        status = 'approved';
      } else if (finalizedCount > 0 && finalizedCount >= totalStudents) {
        status = 'finalized';
      }

      return {
        classId: c.id,
        className: c.className,
        homeroomTeacher: c.homeroomTeacher?.fullName || 'Belum Ditunjuk',
        totalStudents,
        finalizedCount,
        approvedCount,
        status,
      };
    });

    return res.json({ overview, activeYear });
  } catch (error) {
    return res.status(500).json({ message: 'Gagal mengambil monitoring kelas.' });
  }
};

export const approveClassReport = async (req: AuthRequest, res: Response) => {
  try {
    const { classId } = req.body;
    const activeYear = await prisma.academicYear.findFirst({ where: { isActive: true } });
    if (!activeYear) {
      return res.status(400).json({ message: 'Tidak ada tahun ajaran aktif.' });
    }

    await prisma.studentAttendanceRemark.updateMany({
      where: {
        classId,
        academicYearId: activeYear.id,
      },
      data: { status: 'approved' },
    });

    return res.json({ message: 'Raport kelas berhasil disetujui (Approved) dan siap didistribusikan.' });
  } catch (error) {
    return res.status(500).json({ message: 'Gagal menyetujui raport.' });
  }
};
