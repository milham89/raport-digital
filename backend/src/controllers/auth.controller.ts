import { Response } from 'express';
import bcrypt from 'bcryptjs';
import jwt from 'jsonwebtoken';
import { prisma } from '../config/database';
import { JWT_SECRET, JWT_EXPIRES_IN } from '../config/jwt';
import { AuthRequest } from '../middlewares/auth.middleware';

export const login = async (req: AuthRequest, res: Response) => {
  try {
    const { username, password } = req.body;
    if (!username || !password) {
      return res.status(400).json({ message: 'Username dan password wajib diisi.' });
    }

    const user = await prisma.user.findUnique({ where: { username } });
    if (!user || !user.isActive) {
      return res.status(401).json({ message: 'Username atau password salah / Akun tidak aktif.' });
    }

    const isMatch = await bcrypt.compare(password, user.passwordHash);
    if (!isMatch) {
      return res.status(401).json({ message: 'Username atau password salah.' });
    }

    const payload = {
      id: user.id,
      username: user.username,
      fullName: user.fullName,
      role: user.role,
    };

    const token = jwt.sign(payload, JWT_SECRET, { expiresIn: JWT_EXPIRES_IN as any });

    return res.json({
      message: 'Login berhasil.',
      token,
      user: {
        id: user.id,
        username: user.username,
        fullName: user.fullName,
        role: user.role,
        nip: user.nip,
      },
    });
  } catch (error) {
    console.error('Error login:', error);
    return res.status(500).json({ message: 'Terjadi kesalahan pada server.' });
  }
};

export const getMe = async (req: AuthRequest, res: Response) => {
  try {
    if (!req.user) {
      return res.status(401).json({ message: 'Tidak terautentikasi.' });
    }
    const user = await prisma.user.findUnique({
      where: { id: req.user.id },
      select: { id: true, username: true, fullName: true, role: true, nip: true, isActive: true },
    });
    return res.json({ user });
  } catch (error) {
    return res.status(500).json({ message: 'Terjadi kesalahan pada server.' });
  }
};
