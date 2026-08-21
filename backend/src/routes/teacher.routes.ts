import { Router } from 'express';
import { getMyAssignedClasses, getGradesByClassAndSubject, batchSaveGrades } from '../controllers/teacher.controller';
import { authenticateToken } from '../middlewares/auth.middleware';
import { verifyRole } from '../middlewares/rbac.middleware';

const router = Router();

router.use(authenticateToken);
router.use(verifyRole(['teacher', 'homeroom']));

router.get('/my-classes', getMyAssignedClasses);
router.get('/grades', getGradesByClassAndSubject);
router.post('/grades/batch', batchSaveGrades);

export default router;
