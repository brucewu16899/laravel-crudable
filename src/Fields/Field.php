<?php

namespace Akibatech\Crud\Fields;

use Akibatech\Crud\Services\CrudEntry;
use Akibatech\Crud\Services\CrudFields;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Field
 *
 * @package Akibatech\Crud\Fields
 */
abstract class Field
{
    /**
     * @var CrudFields
     */
    protected $fields;

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var array
     */
    protected $rules;

    /**
     * Field constructor.
     *
     * @param   string $identifier
     */
    public function __construct($identifier, $rules = null)
    {
        $this->identifier = $identifier;

        if (!is_null($rules))
        {
            $this->withRules($rules);
        }
    }

    /**
     * @param   CrudFields $fields
     * @return  self
     */
    public function setFields(CrudFields $fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Get the field identifier.
     *
     * @param   void
     * @return  string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Returns the field's label.
     *
     * @param   void
     * @return  string
     */
    public function getLabel()
    {
        if (empty($this->label))
        {
            return title_case($this->identifier);
        }

        return $this->label;
    }

    /**
     * Set a custom label for the field.
     *
     * @param   string $name
     * @return  self
     */
    public function withLabel($name)
    {
        $this->label = $name;

        return $this;
    }

    /**
     * Returns the field's form.
     *
     * @param   void
     * @return  string
     */
    public function form()
    {
        $view = view()->make($this->getViewName())->with([
            'field' => $this,
            'label' => $this->getLabel(),
            'name'  => $this->identifier,
            'id'    => 'field-' . $this->identifier,
            'value' => $this->getValue()
        ]);

        return $view->render();
    }

    /**
     * Get the field view name.
     *
     * @param   void
     * @return  string
     */
    abstract public function getViewName();

    /**
     * Get the field value.
     *
     * @param   void
     * @return  mixed
     */
    public function getValue()
    {
        return $this->fields->getEntry()->getModel()->getAttributeValue($this->identifier);
    }

    /**
     * Set a new value to the model.
     *
     * @param   mixed $value
     * @return  self
     */
    public function newValue($value)
    {
        $this->fields->getEntry()->getModel()->setAttribute($this->identifier, $value);

        return $this;
    }

    /**
     * Add validation rules to the field.
     *
     * @param   string|array $rules
     * @return  mixed
     */
    public function withRules($rules)
    {
        if (is_array($rules))
        {
            foreach ($rules as $rule)
            {
                $this->addRule($rule);
            }
        }
        else if (is_string($rules))
        {
            if (stripos($rules, '|') !== false)
            {
                $rules = explode('|', $rules);

                return $this->withRules($rules);
            }

            return $this->withRules([$rules]);
        }

        return $this;
    }

    /**
     * Add a validation rule.
     *
     * @param   string $rule
     * @return  self
     */
    public function addRule($rule)
    {
        $this->rules[] = $rule;

        return $this;
    }

    /**
     * @param   void
     * @return  string
     */
    public function getRules()
    {
        return $this->rules;
    }
}
