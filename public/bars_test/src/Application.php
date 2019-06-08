<?php

class Application
{
	protected $tz = 'America/Tijuana';

	public function run()
	{
		$users = $this->getUsers();

		$users = $this->transform($users);

		$this->render($users);
	}

	protected function getUsers()
	{
		return [
			[
				'name' => 'luis',
				'tz' => 'America/Tijuana',
			],
			[
				'name' => 'paco',
				'tz' => 'America/Mazatlan',
			],
			[
				'name' => 'hector',
				'tz' => 'America/Mexico_City',
			],
		];
	}

	protected function transform(array $users)
	{
		$items = [];

		foreach ($users as $user) {

			$max = $this->getDateTime($user['tz'], 18, 0);

			$min = $this->getDateTime($user['tz'], 8, 30);
			
			$interval = $max->diff($min);

			$items[] = [
				'name' => $user['name'],
				'timezone' => $user['tz'],
				'arrival' => $min->format('Y-m-d H:i A'),
				'departure' => $max->format('Y-m-d H:i A'),
				'hours' => [
					'hour' => $interval->h,
					'minute' => $interval->i,
				],
			];
		}

		return $items;
	}

	protected function getDateTime($timezone, $hour, $minute)
	{
		$dt = new DateTime();

		$dt->setTimezone(new DateTimeZone($timezone));

		$dt->setTime($hour, $minute);

		$dt->setTimezone(new DateTimeZone($this->tz));

		return $dt;
	}

	protected function render(array $users)
	{
		require __DIR__.'/views/index.php';
	}
}
