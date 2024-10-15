<?php

const MAX_EMAIL_LENGTH = 129;
const EMAIL_REGEXP = '/^[a-zA-Z0-9_]+([.-]?[a-zA-Z0-9]+)*@[a-zA-Z0-9]+([.-]?[a-zA-Z0-9]+)*\.[a-zA-Z]{2,15}$/';
const DATE_FORMAT = 'Y-m-d H:i:s';
const CONFIRM_EMAIL_ID_LENGTH = 15;
const CONFIRM_REPASS_ID_LENGTH = 15;
const MIN_PASSWORD_LENGTH = 1;
const MAX_PASSWORD_LENGTH = 50;
const ALL_NUMBERS = '0123456789';
const ALL_LETTERS = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
const REFRESH_TOKEN_LENGTH = 100;
const USER_SESSIONS_MAX_COUNT = 5;
const MAX_FIRST_NAME_LENGTH = 30;
const MAX_LAST_NAME_LENGTH = 30;
const FIRST_LAST_NAME_REGEXP = '/^(?!-)(?!.*--)[\p{L}-]+(?<!-)$/u';

class LanguageCodes {
  const RU = 'RU';
  const ENG = 'ENG';
}

const API_MESSAGES = [
  'ROUTE_NOT_EXIST' => [
      LanguageCodes::ENG => 'There is no such route',
      LanguageCodes::RU => 'Такого адреса не существует'
  ],
  'NO_ROUTES_IN_URL' => [
    LanguageCodes::ENG => 'URL does not contain any routes',
    LanguageCodes::RU => 'URL не содержит никаких маршрутов'
  ],
  'REQUEST_BODY_EMPTY' => [
    LanguageCodes::ENG => 'Request body is empty',
    LanguageCodes::RU => 'Тело запроса пустое'
  ],
  'REQUEST_BODY_SHOULD_BE_EMPTY' => [
    LanguageCodes::ENG => 'Request body should be empty',
    LanguageCodes::RU => 'Тело запроса должно быть пустым'
  ],
  'REQUEST_BODY_IS_WRONG' => [
    LanguageCodes::ENG => 'Request body is wrong',
    LanguageCodes::RU => 'Тело запроса некорректно'
  ],
  'EMAIL_NOT_VALID' => [
    LanguageCodes::ENG => 'Email not valid',
    LanguageCodes::RU => 'Email некорректен'
  ],
  'PASSWORDS_NOT_MATCH' => [
    LanguageCodes::ENG => 'Passwords not match',
    LanguageCodes::RU => 'Пароли не совпадают'
  ],
  'TOO_LONG_EMAIL' => [
    LanguageCodes::ENG => 'Email is too long, maximum '.MAX_EMAIL_LENGTH.' symbols',
    LanguageCodes::RU => 'Email слишком длинный, максимум '.MAX_EMAIL_LENGTH.' символов'
  ],
  'DB_NOT_CONNECTED' => [
    LanguageCodes::ENG => 'Failed to connect to the database',
    LanguageCodes::RU => 'Не удалось подключиться к базе данных'
  ],
  'USER_ALREADY_EXIST' => [
    LanguageCodes::ENG => 'User already exist',
    LanguageCodes::RU => 'Такой пользователь уже существует'
  ],
  'USER_NOT_REGISTERED' => [
    LanguageCodes::ENG => 'This user is not registered yet',
    LanguageCodes::RU => 'Пользователь ещё не зарегистрирован'
  ],
  'USER_NOT_CREATED' => [
    LanguageCodes::ENG => 'User not created, try again later',
    LanguageCodes::RU => 'Пользователь не создан, попробуйте позже'
  ],
  'USER_CREATED' => [
    LanguageCodes::ENG => 'User has been successfully created, check your email',
    LanguageCodes::RU => 'Пользователь успешно создан, проверьте email'
  ],
  'CONFIRM_ID_TYPE_NOT_CORRECT' => [
    LanguageCodes::ENG => 'Confirm ID should contains only numbers',
    LanguageCodes::RU => 'ID одтверждения должен состоять только из цифр'
  ],
  'CONFIRM_ID_LENGTH_NOT_CORRECT' => [
    LanguageCodes::ENG => 'Confirm ID has not correct length',
    LanguageCodes::RU => 'ID подтверждения неправильной длины'
  ],
  'USER_CONFIRMED' => [
    LanguageCodes::ENG => 'User confirmed, you can login',
    LanguageCodes::RU => 'Пользователь подтверждён, теперь вы можете войти'
  ],
  'CONFIRM_ID_IS_EXPIRED' => [
    LanguageCodes::ENG => 'This confirm ID is expired',
    LanguageCodes::RU => 'У ID подтверждения истёк срок действия'
  ],
  'TOO_SHORT_PASSWORD' => [
    LanguageCodes::ENG => 'Too short password',
    LanguageCodes::RU => 'Слишком короткий пароль'
  ],
  'TOO_LONG_PASSWORD' => [
    LanguageCodes::ENG => 'Too long password',
    LanguageCodes::RU => 'Слишком длинный пароль'
  ],
  'USER_NOT_CONFIRMED' => [
    LanguageCodes::ENG => 'User not confirmed',
    LanguageCodes::RU => 'Пользователь не подтверждён'
  ],
  'PASSWORD_NOT_CORRECT' => [
    LanguageCodes::ENG => 'Password not correct',
    LanguageCodes::RU => 'Неправильный пароль'
  ],
  'USER_IS_LOGGED_IN' => [
    LanguageCodes::ENG => 'User is logged in',
    LanguageCodes::RU => 'Пользователь авторизован'
  ],
  'USER_IS_NOT_LOGGED_IN' => [
    LanguageCodes::ENG => 'User is not logged in',
    LanguageCodes::RU => 'Пользователь не авторизован'
  ],
  'COOCKIE_NOT_FOUND' => [
    LanguageCodes::ENG => 'Refresh coockie not found',
    LanguageCodes::RU => 'Рефреш кука не найдена'
  ],
  'REFRESH_TOKEN_EXPIRED' => [
    LanguageCodes::ENG => 'Refresh token expired, you should login',
    LanguageCodes::RU => 'Рефреш токен истёк, вам нужно залогиниться'
  ],
  'SESSION_UPDATED' => [
    LanguageCodes::ENG => 'Session updated',
    LanguageCodes::RU => 'Сессия обновлена'
  ],
  'SESSION_NOT_UPDATED' => [
    LanguageCodes::ENG => 'Session not updated',
    LanguageCodes::RU => 'Сессия не обновлена'
  ],
  'REPASS_NOT_STARTED' => [
    LanguageCodes::ENG => 'Repass not started, try again later',
    LanguageCodes::RU => 'Восстановление пароля не запущено, попробуйте позже'
  ],
  'REPASS_STARTED' => [
    LanguageCodes::ENG => 'Repass successfully started, check your email',
    LanguageCodes::RU => 'Восстановление пароля запущено, проверьте email'
  ],
  'REPASS_ID_TYPE_NOT_CORRECT' => [
    LanguageCodes::ENG => 'Repass ID should contains only numbers',
    LanguageCodes::RU => 'ID восстановления должен содержать только цифры'
  ],
  'PASSWORD_CHANGED' => [
    LanguageCodes::ENG => 'Password successfully changed, you can login with new one',
    LanguageCodes::RU => 'Пароль успешно изменён, вы можете войти'
  ],
  'PASSWORD_NOT_CHANGED' => [
    LanguageCodes::ENG => 'Password not changed, try again later',
    LanguageCodes::RU => 'Пароль не изменён, попробуйте позже'
  ],
  'ACCESS_NOT_ALLOWED' => [
    LanguageCodes::ENG => 'User not logged in or access token expired',
    LanguageCodes::RU => 'Пользователь не залогинен или токен доступа истёк'
  ],
  'NOT_ENOUGH_RIGHTS' => [
    LanguageCodes::ENG => 'Not enough rights',
    LanguageCodes::RU => 'Не достаточно прав'
  ],
  'NO_ACCESS_TOKEN' => [
    LanguageCodes::ENG => 'Access token not detected',
    LanguageCodes::RU => 'Токен доступа не обнаружен'
  ],
  'USER_NOT_EXISTS' => [
    LanguageCodes::ENG => 'User not exists',
    LanguageCodes::RU => 'Пользователя не существует'
  ],
  'METHOD_NOT_SUPPORTED' => [
    LanguageCodes::ENG => 'This method is not supported',
    LanguageCodes::RU => 'Метод не поддерживается'
  ],
  'USER_LOGGED_OUT' => [
    LanguageCodes::ENG => 'User has been successfully logged out',
    LanguageCodes::RU => 'Пользователь успешно разлогинен'
  ],
  'USER_ALREADY_LOGGED_OUT' => [
    LanguageCodes::ENG => 'User already logged out',
    LanguageCodes::RU => 'Пользователь уже разлогинен'
  ],
  'TOO_LONG_FIRST_NAME' => [
    LanguageCodes::ENG => 'First name is too long',
    LanguageCodes::RU => 'Слишком длинное имя'
  ],
  'TOO_LONG_LAST_NAME' => [
    LanguageCodes::ENG => 'Last name is too long',
    LanguageCodes::RU => 'Слишком длинная фамилия'
  ],
  'FIRST_NAME_NOT_VALID' => [
    LanguageCodes::ENG => 'First name not valid',
    LanguageCodes::RU => 'Имя некорректно'
  ],
  'LAST_NAME_NOT_VALID' => [
    LanguageCodes::ENG => 'Last name not valid',
    LanguageCodes::RU => 'Фамилия некорректна'
  ]
];

class Paths {
  const ROUTES_DIR = 'routes/';
}

class Symbols {
  const SLASH = '/';
  const EMPTY_STRING = '';
  const DOT = '.';
  const SPACE = ' ';
}

class Methods {
  const GET = 'GET';
  const POST = 'POST';
  const PATCH = 'PATCH';
  const OPTIONS = 'OPTIONS';
}

class MailSubjects {
  const CONFIRM_EMAIL = 'Please, confirm your email';
  const REPASS_EMAIL = 'Please, set new password';
}

class StrToTime {
  const ACCESS_TOKEN_EXPIRE = '+ 10 minutes';
  const REFRESH_TOKEN_EXPIRE = '+ 1 month';
  const NOW = 'now';
}

class UserRoles {
  const OWNER = 'owner';
  const VOLUNTEER = 'volunteer';
}
