import { Router } from 'express';
import { getPrintableReport } from '../controllers/report.controller';
import { authenticateToken } from '../middlewares/auth.middleware';
import { verifyRole } from '../middlewares/rbac.middleware';

const router = Router();

router.use(authenticateToken);
router.use(verifyRole(['admin', 'homeroom', 'principal']));

router.get('/print/:studentId', getPrintableReport);

export default router;
