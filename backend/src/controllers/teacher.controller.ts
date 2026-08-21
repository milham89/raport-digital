import { Response } from 'express';
import { prisma } from '../config/database';
import { AuthRequest } from '../middlewares/auth.middleware';
import { calculateGrade } from '../services/gradeCalculation.service';

export const getMyAssignedClasses = async (req: AuthRequest, res: Response) => {
  try {
    const teacherId = req.user?.id;
    const activeYear = await prisma.academicYear.findFirst({ where: { isActive: true } });

    if (!activeYear) {
      return res.status(400).json({ message: 'Tidak ada tahun ajaran aktif.' });
    }

    const assignments = await prisma.teacherAssignment.findMany({
      where: {
        teacherId,
        academicYearId: activeYear.id,
      },
      include: {
        class: true,
        subject: true,
      },
    });

    return res.json({ assignments, activeYear });
  } catch (error) {
    return res.status(500).json({ message: 'Gagal mengambil penugasan mengajar.' });
  }
};

export const getGradesByClassAndSubject = async (req: AuthRequest, res: Response) => {
  try {
    const { classId, subjectId } = req.query;
    if (!classId || !subjectId) {
      return res.status(400).json({ message: 'classId dan subjectId wajib diisi.' });
    }

    const activeYear = await prisma.academicYear.findFirst({ where: { isActive: true } });
    if (!activeYear) {
      return res.status(400).json({ message: 'Tidak ada tahun ajaran aktif.' });
    }

    // Get students in this class
    const students = await prisma.student.findMany({
      where: { classId: String(classId), isActive: true },
      orderBy: { fullName: 'asc' },
    });

    // Get existing grades
    const grades = await prisma.grade.findMany({
      where: {
        classId: String(classId),
        subjectId: String(subjectId),
        academicYearId: activeYear.id,
      },
    });

    const gradeMap = new Map();
    grades.forEach((g) => gradeMap.set(g.studentId, g));

    const result = students.map((s) => {
      const g = gradeMap.get(s.id);
      return {
        studentId: s.id,
        nis: s.nis,
        fullName: s.fullName,
        gradeId: g ? g.id : null,
        assignmentScore: g ? g.assignmentScore : 0,
        midScore: g ? g.midScore : 0,
        finalScore: g ? g.finalScore : 0,
        finalGrade: g ? g.finalGrade : 0,
        predicate: g ? g.predicate : 'D',
        feedback: g ? g.feedback : '',
      };
    });

    return res.json({ students: result, activeYear });
  } catch (error) {
    return res.status(500).json({ message: 'Gagal mengambil data nilai siswa.' });
  }
};

export const batchSaveGrades = async (req: AuthRequest, res: Response) => {
  try {
    const teacherId = req.user?.id;
    const { classId, subjectId, grades } = req.body; // grades: [{ studentId, assignmentScore, midScore, finalScore, feedback }]

    if (!teacherId || !classId || !subjectId || !Array.isArray(grades)) {
      return res.status(400).json({ message: 'Data input nilai tidak valid.' });
    }

    const activeYear = await prisma.academicYear.findFirst({ where: { isActive: true } });
    if (!activeYear) {
      return res.status(400).json({ message: 'Tidak ada tahun ajaran aktif.' });
    }

    const subject = await prisma.subject.findUnique({ where: { id: subjectId } });

    const savedGrades = [];
    for (const item of grades) {
      const calc = calculateGrade(
        {
          assignmentScore: Number(item.assignmentScore) || 0,
          midScore: Number(item.midScore) || 0,
          finalScore: Number(item.finalScore) || 0,
        },
        subject?.name || 'Mata Pelajaran'
      );

      const gradeRecord = await prisma.grade.upsert({
        where: {
          studentId_subjectId_academicYearId: {
            studentId: item.studentId,
            subjectId,
            academicYearId: activeYear.id,
          },
        },
        update: {
          teacherId,
          classId,
          assignmentScore: Number(item.assignmentScore) || 0,
          midScore: Number(item.midScore) || 0,
          finalScore: Number(item.finalScore) || 0,
          finalGrade: calc.finalGrade,
          predicate: calc.predicate,
          feedback: item.feedback || calc.feedback,
        },
        create: {
          studentId: item.studentId,
          subjectId,
          teacherId,
          classId,
          academicYearId: activeYear.id,
          assignmentScore: Number(item.assignmentScore) || 0,
          midScore: Number(item.midScore) || 0,
          finalScore: Number(item.finalScore) || 0,
          finalGrade: calc.finalGrade,
          predicate: calc.predicate,
          feedback: item.feedback || calc.feedback,
        },
      });
      savedGrades.push(gradeRecord);
    }

    return res.json({ message: 'Nilai berhasil disimpan.', count: savedGrades.length });
  } catch (error) {
    console.error('Error saving grades:', error);
    return res.status(500).json({ message: 'Gagal menyimpan data nilai.' });
  }
};
