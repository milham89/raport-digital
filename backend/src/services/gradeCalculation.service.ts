export interface GradeInput {
  assignmentScore: number;
  midScore: number;
  finalScore: number;
}

export interface CalculatedGrade {
  finalGrade: number;
  predicate: 'A' | 'B' | 'C' | 'D';
  feedback: string;
}

export function calculateGrade(input: GradeInput, subjectName: string = 'Mata Pelajaran'): CalculatedGrade {
  const assignmentScore = Math.max(0, Math.min(100, Number(input.assignmentScore) || 0));
  const midScore = Math.max(0, Math.min(100, Number(input.midScore) || 0));
  const finalScore = Math.max(0, Math.min(100, Number(input.finalScore) || 0));

  // Formula: (Tugas * 0.30) + (UTS * 0.30) + (UAS * 0.40)
  const rawFinal = (assignmentScore * 0.30) + (midScore * 0.30) + (finalScore * 0.40);
  const finalGrade = Math.round(rawFinal * 100) / 100;

  let predicate: 'A' | 'B' | 'C' | 'D' = 'D';
  let feedback = '';

  if (finalGrade >= 90) {
    predicate = 'A';
    feedback = `Sangat Baik. Menunjukkan penguasaan materi ${subjectName} dengan luar biasa.`;
  } else if (finalGrade >= 80) {
    predicate = 'B';
    feedback = `Baik. Menunjukkan penguasaan materi ${subjectName} dengan cukup stabil.`;
  } else if (finalGrade >= 70) {
    predicate = 'C';
    feedback = `Cukup. Menunjukkan penguasaan standar ${subjectName}, perlu ditingkatkan lagi.`;
  } else {
    predicate = 'D';
    feedback = `Perlu Bimbingan. Masih membutuhkan pendampingan ekstra pada materi ${subjectName}.`;
  }

  return { finalGrade, predicate, feedback };
}
