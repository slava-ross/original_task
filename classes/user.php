<?php
	class user {
        /**
         * -D - Локальный защищённый массив конфигурации;
         * -V- {Array} @config: Конфиг;
         */
		private $config = NULL;

        /**
         * -D, Method- Сеттер конфигурации;
         * -V- {Array} @config: Массив настроек;
         */
        public function setConfig($config) {
            $this->config = $config;
        }

		/**
		 * -D, Method- Авторизация пользователя;
		 * -R- Array(
			'success'	=> (bool),		// true - пользователь авторизован, false - есть ошибки;
			'errors'	=> array(),		// массив ошибок в строчном виде;
            );
		 */
		public function auth( $login, $pass ) {
			$success = false;
			$errors = array();

			$login = trim( $login );
			$pass = trim( $pass );

            if ($login === '') {
				$errors[] = "Укажите Ваш логин!";
			} elseif ($pass === '') {
				$errors[] = "Укажите Ваш пароль!";
			} elseif ($login !== $this->config['ADMIN_LOGIN']) {
                $errors[] = "Пользователь не зарегистрирован!";
            } elseif ($pass !== $this->config['ADMIN_PASS']) {
                $errors[] = "Неверный пароль!";
            } else {
                $_SESSION['user'] = $login;
			    $success = true;
            }
			return array(
				'success'	=> $success,
				'errors'	=> $errors
			);
		}

		/**
		*	-D, Method- Метод выполняющий проверку авторизации пользователя по сессионной переменной;
		*/
		public function isAuth() {
			return (!empty($_SESSION['user']));
		}

        /**
		*	-D, Method- Метод выполняющий завершение сессии пользователя;
		*/
		public function logout() {
            session_start();
            session_destroy();
            header('Location: /');
            die;
		}
	}
?>