import { Router } from 'express';
import { getMonitoringOverview, approveClassReport } from '../controllers/principal.controller';
import { authenticateToken } from '../middlewares/auth.middleware';
import { verifyRole } from '../middlewares/rbac.middleware';

const router = Router();

router.use(authenticateToken);
router.use(verifyRole(['principal']));

router.get('/monitoring', getMonitoringOverview);
router.post('/approve-report', approveClassReport);

export default router;
