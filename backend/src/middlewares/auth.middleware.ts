import { Request, Response, NextFunction } from 'express';
import jwt from 'jsonwebtoken';
import { JWT_SECRET } from '../config/jwt';

export interface UserPayload {
  id: string;
  username: string;
  fullName: string;
  role: 'admin' | 'teacher' | 'homeroom' | 'principal';
}

export interface AuthRequest extends Request {
  user?: UserPayload;
}

export const authenticateToken = (req: AuthRequest, res: Response, next: NextFunction) => {
  const authHeader = req.headers.authorization;
  const token = authHeader && authHeader.split(' ')[1];

  if (!token) {
    return res.status(401).json({ message: 'Akses ditolak. Token autentikasi tidak ditemukan.' });
  }

  try {
    const decoded = jwt.verify(token, JWT_SECRET) as UserPayload;
    req.user = decoded;
    next();
  } catch (err) {
    return res.status(403).json({ message: 'Token tidak valid atau telah kedaluwarsa.' });
  }
};
