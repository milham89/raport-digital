import dotenv from 'dotenv';
dotenv.config();

export const JWT_SECRET = process.env.JWT_SECRET || 'raport_super_secret_jwt_key_2026';
export const JWT_EXPIRES_IN = process.env.JWT_EXPIRES_IN || '1d';
