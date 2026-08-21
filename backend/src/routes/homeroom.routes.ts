import { Router } from 'express';
import { getMyHomeroomClass, saveAttendanceAndRemarks, lockClassReport } from '../controllers/homeroom.controller';
import { authenticateToken } from '../middlewares/auth.middleware';
import { verifyRole } from '../middlewares/rbac.middleware';

const router = Router();

router.use(authenticateToken);
router.use(verifyRole(['homeroom']));

router.get('/my-homeroom-class', getMyHomeroomClass);
router.post('/attendances-remarks', saveAttendanceAndRemarks);
router.post('/lock-report', lockClassReport);

export default router;
