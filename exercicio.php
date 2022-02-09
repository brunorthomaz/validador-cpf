<?php

class Person
{
	const ACCEPTED_LENGTH = 11;
	const FIRST_DIGIT_POSITION = 9;
	const SECOND_DIGIT_POSITION = 10;
	const DIGIT_LIMIT_TO_BE_ZERO = 2;

	/**
	 * @param string $cpf
	 * @return int
	 */
	protected function getVerificationDigit(string $cpf): int
	{
		$digit = 0;
		$cpfLength = strlen($cpf);
		
		for($currentPosition = 0;$currentPosition < $cpfLength;$currentPosition++) {
			$digit += $cpf[$currentPosition] * (($cpfLength + 1) - $currentPosition);
		}

		return $this->calculateDigit($digit);
	}

	/**
	 * @param int $digit
	 * @return int
	 */
	protected function calculateDigit(int $digit): int
	{
		$rest = $digit % self::ACCEPTED_LENGTH;
		if($rest < self::DIGIT_LIMIT_TO_BE_ZERO) {
			return 0;
		}

		return self::ACCEPTED_LENGTH - $rest;
	}

	/**
	 * @param string $cpf
	 * @return string
	 */
	protected function filterInput(string $cpf): string
	{
		return preg_replace('/[^0-9]/is', '', $cpf);
	}

	/**
	 * @param string $cpf
	 * @return bool
	 */
	protected function checkEqualDigits(string $cpf): bool
	{
		return substr_count($cpf, $cpf[0]) == strlen($cpf);
	}

	/**
	 * @param string $cpf
	 * @return bool
	 */
	public function isValidCpf(string $cpf): bool
	{
		$cpf = $this->filterInput($cpf);
		$isInvalid = strlen($cpf) != self::ACCEPTED_LENGTH || empty($cpf) || $this->checkEqualDigits($cpf);

		if($isInvalid) {
			return false;
		}

		$firstDigit = $this->getVerificationDigit(substr($cpf, 0, self::FIRST_DIGIT_POSITION));
		$secondDigit = $this->getVerificationDigit(substr($cpf, 0, self::SECOND_DIGIT_POSITION));

		return $firstDigit == $cpf[self::FIRST_DIGIT_POSITION] && $secondDigit == $cpf[self::SECOND_DIGIT_POSITION];
	}
}