import express from 'express';
import cors from 'cors';
import dotenv from 'dotenv';

import authRoutes from './routes/auth.routes';
import adminRoutes from './routes/admin.routes';
import teacherRoutes from './routes/teacher.routes';
import homeroomRoutes from './routes/homeroom.routes';
import principalRoutes from './routes/principal.routes';
import reportRoutes from './routes/report.routes';

dotenv.config();

const app = express();

app.use(cors());
app.use(express.json());

// Base Route
app.get('/', (req, res) => {
  res.json({
    name: 'Aplikasi Raport Sekolah Multi-Role API',
    status: 'Running',
    version: '1.0.0',
    endpoints: {
      health: '/health',
      auth: '/api/v1/auth',
      admin: '/api/v1/admin',
      teacher: '/api/v1/teacher',
      homeroom: '/api/v1/homeroom',
      principal: '/api/v1/principal',
      reports: '/api/v1/reports',
    },
  });
});

// Routes
app.use('/api/v1/auth', authRoutes);
app.use('/api/v1/admin', adminRoutes);
app.use('/api/v1/teacher', teacherRoutes);
app.use('/api/v1/homeroom', homeroomRoutes);
app.use('/api/v1/principal', principalRoutes);
app.use('/api/v1/reports', reportRoutes);

app.get('/health', (req, res) => {
  res.json({ status: 'OK', message: 'Aplikasi Raport API Server Running' });
});

export default app;

