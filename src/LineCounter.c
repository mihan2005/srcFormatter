package ru.vsu.cs.piit.java;

public class LineCounter {

	public double CountSum(int length) {
		double sum = 0;
		for (int i = 1; i < length; i++) {
			sum += 1 / Math.pow(i, 3);
		}
		return sum;
	}
	
}
