import { Router } from 'express';
import {
  getUsers,
  createUser,
  updateUser,
  getAcademicYears,
  createAcademicYear,
  setActiveAcademicYear,
  getClasses,
  createClass,
  assignHomeroom,
  getSubjects,
  createSubject,
  getStudents,
  createStudent,
  getTeacherAssignments,
  createTeacherAssignment,
} from '../controllers/admin.controller';
import { authenticateToken } from '../middlewares/auth.middleware';
import { verifyRole } from '../middlewares/rbac.middleware';

const router = Router();

router.use(authenticateToken);
router.use(verifyRole(['admin']));

// Users
router.get('/users', getUsers);
router.post('/users', createUser);
router.put('/users/:id', updateUser);

// Academic Years
router.get('/academic-years', getAcademicYears);
router.post('/academic-years', createAcademicYear);
router.put('/academic-years/:id/set-active', setActiveAcademicYear);

// Classes
router.get('/classes', getClasses);
router.post('/classes', createClass);
router.put('/classes/:id/homeroom', assignHomeroom);

// Subjects
router.get('/subjects', getSubjects);
router.post('/subjects', createSubject);

// Students
router.get('/students', getStudents);
router.post('/students', createStudent);

// Teacher Assignments
router.get('/teacher-assignments', getTeacherAssignments);
router.post('/teacher-assignments', createTeacherAssignment);

export default router;
