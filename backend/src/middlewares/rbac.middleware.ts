import { Response, NextFunction } from 'express';
import { AuthRequest } from './auth.middleware';

export const verifyRole = (allowedRoles: Array<'admin' | 'teacher' | 'homeroom' | 'principal'>) => {
  return (req: AuthRequest, res: Response, next: NextFunction) => {
    if (!req.user) {
      return res.status(401).json({ message: 'Autentikasi pengguna diperlukan.' });
    }

    if (!allowedRoles.includes(req.user.role)) {
      return res.status(403).json({ message: `Akses ditolak. Peran '${req.user.role}' tidak diizinkan mengakses resource ini.` });
    }

    next();
  };
};
