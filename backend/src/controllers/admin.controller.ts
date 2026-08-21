import { Response } from 'express';
import bcrypt from 'bcryptjs';
import { prisma } from '../config/database';
import { AuthRequest } from '../middlewares/auth.middleware';

// USERS
export const getUsers = async (req: AuthRequest, res: Response) => {
  try {
    const users = await prisma.user.findMany({
      select: { id: true, username: true, fullName: true, nip: true, role: true, isActive: true, createdAt: true },
      orderBy: { createdAt: 'desc' },
    });
    return res.json({ users });
  } catch (error) {
    return res.status(500).json({ message: 'Gagal mengambil data user.' });
  }
};

export const createUser = async (req: AuthRequest, res: Response) => {
  try {
    const { username, password, fullName, nip, role } = req.body;
    const existing = await prisma.user.findUnique({ where: { username } });
    if (existing) {
      return res.status(400).json({ message: 'Username sudah digunakan.' });
    }
    const passwordHash = await bcrypt.hash(password || 'password123', 10);
    const newUser = await prisma.user.create({
      data: { username, passwordHash, fullName, nip, role: role || 'teacher' },
    });
    return res.status(201).json({ message: 'User berhasil dibuat.', user: newUser });
  } catch (error) {
    return res.status(500).json({ message: 'Gagal membuat user.' });
  }
};

export const updateUser = async (req: AuthRequest, res: Response) => {
  try {
    const { id } = req.params;
    const { fullName, nip, role, isActive, password } = req.body;

    const dataToUpdate: any = { fullName, nip, role, isActive };
    if (password) {
      dataToUpdate.passwordHash = await bcrypt.hash(password, 10);
    }

    const updated = await prisma.user.update({
      where: { id },
      data: dataToUpdate,
    });
    return res.json({ message: 'User berhasil diperbarui.', user: updated });
  } catch (error) {
    return res.status(500).json({ message: 'Gagal memperbarui user.' });
  }
};

// ACADEMIC YEARS
export const getAcademicYears = async (req: AuthRequest, res: Response) => {
  try {
    const years = await prisma.academicYear.findMany({ orderBy: { createdAt: 'desc' } });
    return res.json({ academicYears: years });
  } catch (error) {
    return res.status(500).json({ message: 'Gagal mengambil data tahun ajaran.' });
  }
};

export const createAcademicYear = async (req: AuthRequest, res: Response) => {
  try {
    const { yearName, semester, isActive } = req.body;
    if (isActive) {
      await prisma.academicYear.updateMany({ data: { isActive: false } });
    }
    const year = await prisma.academicYear.create({
      data: { yearName, semester, isActive: isActive || false },
    });
    return res.status(201).json({ message: 'Tahun ajaran berhasil dibuat.', academicYear: year });
  } catch (error) {
    return res.status(500).json({ message: 'Gagal membuat tahun ajaran.' });
  }
};

export const setActiveAcademicYear = async (req: AuthRequest, res: Response) => {
  try {
    const { id } = req.params;
    await prisma.academicYear.updateMany({ data: { isActive: false } });
    const year = await prisma.academicYear.update({
      where: { id },
      data: { isActive: true },
    });
    return res.json({ message: 'Tahun ajaran aktif diubah.', academicYear: year });
  } catch (error) {
    return res.status(500).json({ message: 'Gagal mengubah tahun ajaran aktif.' });
  }
};

export const getClasses = async (req: AuthRequest, res: Response) => {
  try {
    const classes = await prisma.class.findMany({
      include: {
        academicYear: true,
        homeroomTeacher: { select: { id: true, fullName: true, nip: true } },
        _count: { select: { students: true } },
      },
      orderBy: { className: 'asc' },
    });
    return res.json({ classes });
  } catch (error) {
    return res.status(500).json({ message: 'Gagal mengambil data kelas.' });
  }
};

export const createClass = async (req: AuthRequest, res: Response) => {
  try {
    const { className, academicYearId, homeroomTeacherId } = req.body;
    const newClass = await prisma.class.create({
      data: { className, academicYearId, homeroomTeacherId: homeroomTeacherId || null },
    });
    return res.status(201).json({ message: 'Kelas berhasil dibuat.', class: newClass });
  } catch (error) {
    return res.status(500).json({ message: 'Gagal membuat kelas.' });
  }
};

export const assignHomeroom = async (req: AuthRequest, res: Response) => {
  try {
    const { id } = req.params;
    const { homeroomTeacherId } = req.body;
    const updatedClass = await prisma.class.update({
      where: { id },
      data: { homeroomTeacherId },
    });
    return res.json({ message: 'Wali kelas berhasil ditunjuk.', class: updatedClass });
  } catch (error) {
    return res.status(500).json({ message: 'Gagal menunjuk wali kelas.' });
  }
};

// SUBJECTS
export const getSubjects = async (req: AuthRequest, res: Response) => {
  try {
    const subjects = await prisma.subject.findMany({ orderBy: { code: 'asc' } });
    return res.json({ subjects });
  } catch (error) {
    return res.status(500).json({ message: 'Gagal mengambil data mata pelajaran.' });
  }
};

export const createSubject = async (req: AuthRequest, res: Response) => {
  try {
    const { code, name, kkm } = req.body;
    const subject = await prisma.subject.create({
      data: { code, name, kkm: kkm ? parseFloat(kkm) : 75.0 },
    });
    return res.status(201).json({ message: 'Mata pelajaran berhasil dibuat.', subject });
  } catch (error) {
    return res.status(500).json({ message: 'Gagal membuat mata pelajaran.' });
  }
};

// STUDENTS
export const getStudents = async (req: AuthRequest, res: Response) => {
  try {
    const students = await prisma.student.findMany({
      include: { class: { select: { id: true, className: true } } },
      orderBy: { fullName: 'asc' },
    });
    return res.json({ students });
  } catch (error) {
    return res.status(500).json({ message: 'Gagal mengambil data siswa.' });
  }
};

export const createStudent = async (req: AuthRequest, res: Response) => {
  try {
    const { nis, nisn, fullName, gender, classId } = req.body;
    const student = await prisma.student.create({
      data: { nis, nisn, fullName, gender, classId: classId || null },
    });
    return res.status(201).json({ message: 'Siswa berhasil ditambahkan.', student });
  } catch (error) {
    return res.status(500).json({ message: 'Gagal menambah siswa.' });
  }
};

// TEACHER ASSIGNMENTS
export const getTeacherAssignments = async (req: AuthRequest, res: Response) => {
  try {
    const assignments = await prisma.teacherAssignment.findMany({
      include: {
        teacher: { select: { id: true, fullName: true, nip: true } },
        subject: true,
        class: true,
        academicYear: true,
      },
    });
    return res.json({ assignments });
  } catch (error) {
    return res.status(500).json({ message: 'Gagal mengambil penugasan guru.' });
  }
};

export const createTeacherAssignment = async (req: AuthRequest, res: Response) => {
  try {
    const { teacherId, subjectId, classId, academicYearId } = req.body;
    const assignment = await prisma.teacherAssignment.create({
      data: { teacherId, subjectId, classId, academicYearId },
    });
    return res.status(201).json({ message: 'Penugasan guru berhasil dibuat.', assignment });
  } catch (error) {
    return res.status(500).json({ message: 'Gagal membuat penugasan guru.' });
  }
};
