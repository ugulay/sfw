<?php

namespace Kernel;

use Kernel\Input;
use Kernel\Model;

class Validation
{

    private $rules, $method, $fails, $alias, $inputs = null;

    const PREFIX = 'rule_';

    const ALLOWED_RULES = [
        "required",
        "string",
        "numeric",
        "min",
        "max",
        "email",
        "same",
        "equal",
        "greater",
        "greaterEqual",
        "lower",
        "lowerEqual",
        "unique",
        "regex",
    ];

    public function rules($method = null, $rules = [], $alias = [])
    {
        $this->method = $method;
        $this->rules = $rules;
        $this->alias = $alias;
    }

    public function rule_required($key = null)
    {
        $data = $this->getInput($key);
        $key = $this->getAlias($key);
        $message = __('validation.required', $key);
        if (empty($data)) {
            return $message;
        }
        return true;
    }

    public function rule_string($key = null)
    {
        $data = $this->getInput($key);
        $key = $this->getAlias($key);
        $message = __('validation.string', $key);
        if (preg_match('/\d/', $data)) {
            return $message;
        }
        return true;
    }

    public function rule_numeric($key = null)
    {
        $data = $this->getInput($key);
        $key = $this->getAlias($key);
        $message = __('validation.numeric', $key);
        if (preg_match('/\D/', $data)) {
            return $message;
        }
        return true;
    }

    public function rule_min($key = null, $param = 0)
    {
        $data = $this->getInput($key);
        $key = $this->getAlias($key);
        $message = __('validation.min', [$key, $param]);
        if (mb_strlen($data) < (int) $param) {
            return $message;
        }
        return true;
    }

    public function rule_max($key = null, $param = 0)
    {
        $data = $this->getInput($key);
        $key = $this->getAlias($key);
        $message = __('validation.max', [$key, $param]);
        if (mb_strlen($data) > (int) $param) {
            return $message;
        }
        return true;
    }

    public function rule_email($key = null)
    {
        $data = $this->getInput($key);
        $key = $this->getAlias($key);
        $message = __('validation.email', $key);
        if (!filter_var($data, FILTER_VALIDATE_EMAIL)) {
            return $message;
        }
        return true;
    }

    public function rule_same($key = null, $param = null)
    {
        $data = $this->getInput($key);
        $sameData = $this->getInput($param);
        $key = $this->getAlias($key);
        $key2 = $this->getAlias($param);
        $message = __('validation.same', [$key, $key2]);
        if ($data !== $sameData) {
            return $message;
        }
        return true;
    }

    public function rule_equal($key = null, $param = null)
    {
        $data = $this->getInput($key);
        $key = $this->getAlias($key);
        $message = __('validation.equal', [$key, $param]);
        if ($data !== $param) {
            return $message;
        }
        return true;
    }

    public function rule_greater($key = null, $param = null)
    {
        $data = $this->getInput($key);
        $key = $this->getAlias($key);
        $message = __('validation.greater', [$key, $param]);
        if ((int) $data <= (int) $param) {
            return $message;
        }
        return true;
    }

    public function rule_greaterEqual($key = null, $param = null)
    {
        $data = $this->getInput($key);
        $key = $this->getAlias($key);
        $message = __('validation.greaterEqual', [$key, $param]);
        if ((int) $data < (int) $param) {
            return $message;
        }
        return true;
    }

    public function rule_lower($key = null, $param = null)
    {
        $data = $this->getInput($key);
        $key = $this->getAlias($key);
        $message = __('validation.lower', [$key, $param]);
        if ((int) $data >= (int) $param) {
            return $message;
        }
        return true;
    }

    public function rule_lowerEqual($key = null, $param = null)
    {
        $data = $this->getInput($key);
        $key = $this->getAlias($key);
        $message = __('validation.lowerEqual', [$key, $param]);
        if ((int) $data > (int) $param) {
            return $message;
        }
        return true;
    }

    public function rule_regex($key = null, $param = null)
    {
        $data = $this->getInput($key);
        $key = $this->getAlias($key);
        $message = __('validation.regex', [$key, $param]);
        if (!preg_match($param, $data)) {
            return $message;
        }
        return true;
    }

    public function rule_unique($key = null, $param = null)
    {
        $data = $this->getInput($key);
        $key = $this->getAlias($key);

        $message = __('validation.unique', $key);

        $param = explode('.', $param);
        $table = $param[0];
        $col = $param[1];
        $id = false;

        $where = [$col => $data];

        if (isset($param[2])) {
            $id = $param[2];
            $where["AND"][] = ['id[!]' => $id];
            $where["AND"][] = [$col => $data];
        }

        $check = Model::DB()->count($table, $col, $where);

        if ($check) {
            return $message;
        }
        return true;
    }

    public function parseRule($key, $rules)
    {

        $data = Input::{$this->method}($key);

        $this->inputs[$key] = $data;

        $rules = $this->fixRules($rules);

        foreach ($rules as $rule => $param) {
            $this->check($rule, [$key, $param]);
        }

    }

    public function check($rule = null, $param = [])
    {

        if (!in_array($rule, self::ALLOWED_RULES) && !method_exists($this, self::PREFIX . $rule)) {
            throw new \Exception($rule . ' rule method not found.');
        }

        $key = $param[0];

        $res = call_user_func_array([$this, self::PREFIX . $rule], $param);

        if ($res !== true) {
            $this->fails[$key] = $res;
        }
    }

    public function fixRules($rules)
    {
        $newRules = [];
        foreach ($rules as $rule => $param) {
            if (is_numeric($rule) && !is_array($rule)) {
                $rule = $param;
                $param = true;
            }
            $newRules[$rule] = $param;
        }
        return $newRules;
    }

    public function validate()
    {
        foreach ($this->rules as $key => $rules) {
            $this->parseRule($key, $rules);
        }
    }

    public function fails(): bool
    {
        if (is_countable($this->fails)) {
            return true;
        }
        return false;
    }

    public function getFails(): array
    {
        return $this->fails;
    }

    public function getInputs()
    {
        return $this->inputs;
    }

    public function getInput($key = null)
    {
        return $this->inputs[$key];
    }

    public function getAlias($key)
    {
        if (array_key_exists($key, $this->alias)) {
            return $this->alias[$key];
        }
        return $key;
    }

}
