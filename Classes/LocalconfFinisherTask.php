<?php

/**
 * This class is used to get the status of remote source code
 */
require_once('BaseTask.php');

class LocalconfFinisherTask extends BaseTask {

	/**
	 * @var string
	 */
	protected $credentials = '';
	/**
	 * @var string
	 */
	protected $directory = '';

	/**
	 * Main entry point.
	 *
	 * @return void
	 */
	public function main() {

		// Makes sure it is possible to connecto to the server
		if (!file_exists($this->directory)) {
			throw new Exception("Exception thrown #1300533385:\n\n local directory does not exist : \"" . $this->directory . "\"\n\n", 1300533385);
		}

		if (! $this->isDryRun()) {
			// Replaces the database name, user and password
			$content = file_get_contents($this->directory . 'typo3conf/localconf.php');

			$patterns[] = "/typo_db = '[^']+/";
			$patterns[] = "/typo_db_username = '[^']+/";
			$patterns[] = "/typo_db_password = '[^']+/";
			$patterns[] = "/typo_db_host = '[^']+/";
			$patterns[] = "/\?\>/";

			$replacements[] = "typo_db = '" . $this->database;
			$replacements[] = "typo_db_username = '" . $this->username;
			$replacements[] = "typo_db_password = '" . $this->password;
			$replacements[] = "typo_db_host = '" . $this->host;

			// Add no cache line
			$replacement = '$TYPO3_CONF_VARS["EXT"]["extCache"] = \'0\';' . chr(10);
			$replacement .= '$TYPO3_CONF_VARS["SYS"]["cookieDomain"] = \'\';' . chr(10);
			$replacement .= '$TYPO3_CONF_VARS["GFX"]["im_path"] = \'/opt/local/bin/\';' . chr(10);
			$replacement .= "?>" . chr(10);
			$replacements[] = $replacement;

			$content = preg_replace($patterns, $replacements, $content);

			// if dryRun is set then, the command line is printed out
			file_put_contents($this->directory . 'typo3conf/localconf.php', $content);
		}
	}

	/**
	 * Set the remote path on the server
	 *
	 * @param string $value
	 * @return void
	 */
	public function setDirectory($value) {
		$this->directory = $value;
	}

	/**
	 * Set the database
	 *
	 * @param string $value
	 * @return void
	 */
	public function setDatabase($value) {
		$this->database = $value;
	}

	/**
	 * Set the username
	 *
	 * @param string $value
	 * @return void
	 */
	public function setUsername($value) {
		$this->username = $value;
	}

	/**
	 * Set the password
	 *
	 * @param string $value
	 * @return void
	 */
	public function setPassword($value) {
		$this->password = $value;
	}

	/**
	 * Set the password
	 *
	 * @param string $value
	 * @return void
	 */
	public function setHost($value) {
		$this->host = $value;
	}

}