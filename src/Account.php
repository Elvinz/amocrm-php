<?php

    namespace AmoCRM;

    class Account extends Base
    {
        /**
         * Данные по аккаунту
         *
         * Получение информации по аккаунту в котором произведена авторизация:
         * название, оплаченный период, пользователи аккаунта и их права,
         * справочники дополнительных полей контактов и сделок, справочник статусов сделок,
         * справочник типов событий, справочник типов задач и другие параметры аккаунта.
         *
         * @link https://developers.amocrm.ru/rest_api/accounts_current.php
         *
         * @param bool $short
         *
         * @return mixed
         */
        public function apiCurrent($short = false)
        {
            $result = $this->getRequest('/private/api/v2/json/accounts/current');

            return $short ? $this->getShorted($result['account']) : $result['account'];
        }

        private function getShorted($account)
        {
            $keys             = array_fill_keys(['id', 'name', 'login'], 1);
            $account['users'] = array_map(function ($val) use ($keys) {
                return array_intersect_key($val, $keys);
            }, $account['users']);

            $keys                      = array_fill_keys(['id', 'name'], 1);
            $account['leads_statuses'] = array_map(function ($val) use ($keys) {
                return array_intersect_key($val, $keys);
            }, $account['leads_statuses']);

            $keys                  = array_fill_keys(['id', 'name'], 1);
            $account['note_types'] = array_map(function ($val) use ($keys) {
                return array_intersect_key($val, $keys);
            }, $account['note_types']);

            $keys                  = array_fill_keys(['id', 'name'], 1);
            $account['task_types'] = array_map(function ($val) use ($keys) {
                return array_intersect_key($val, $keys);
            }, $account['task_types']);

            $keys = array_fill_keys(['id', 'name', 'type_id', 'enums'], 1);
            foreach ($account['custom_fields'] AS $type => $fields) {
                $account['custom_fields'][$type] = array_map(function ($val) use ($keys) {
                    return array_intersect_key($val, $keys);
                }, $fields);
            }

            $keys                 = array_fill_keys(['id', 'label', 'name'], 1);
            $account['pipelines'] = array_map(function ($val) use ($keys) {
                return array_intersect_key($val, $keys);
            }, $account['pipelines']);

            return $account;
        }
    }