<?php
function convertKelvin($amount, $unit)
{
	if ($unit == 'F')
	{
		$temp = (($amount - 273.15) * 1.8) + 32;
	}
	if ($unit == 'C')
	{
		$temp = ($amount - 273.15);
	}
	if ($unit == False)
	{
		echo "No valid unit of temperature given";
	}
	if ($amount == False)
	{
		echo "No valid amount given";
	}
	return $temp;
}