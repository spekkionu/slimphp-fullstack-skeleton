<?php
namespace Framework\Form;

use Zend\Validator\InArray;
use Zend\Validator\ValidatorInterface;

class FormElement
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type = 'text';

    /**
     * @var bool
     */
    private $required = false;

    /**
     * @var string
     */
    private $message = 'This field is required.';

    /**
     * @var array
     */
    private $filters = [];

    /**
     * @var array
     */
    private $validators = [];
    /**
     * @var string
     */
    private $class;

    /**
     * FormElement constructor.
     *
     * @param string $name
     * @param string $type
     * @param string $class
     */
    public function __construct(string $name, string $type = 'text', $class = 'Zend\InputFilter\Input')
    {
        $this->name  = $name;
        $this->type  = $type;
        $this->class = $class;
    }

    /**
     * @param bool        $required
     * @param string|null $message
     *
     * @return $this
     */
    public function setRequired(bool $required = true, string $message = null)
    {
        $this->required = $required;
        $this->message  = $message ?? 'This field is required.';

        return $this;
    }

    /**
     * @return $this
     */
    public function addEmailValidator()
    {
        $this->validators[] = [
            'name'    => 'EmailAddress',
            'options' => [
                'messages' => [
                    \Zend\Validator\EmailAddress::INVALID            => "Not a valid email address.",
                    \Zend\Validator\EmailAddress::INVALID_FORMAT     => "Not a valid email address.",
                    \Zend\Validator\EmailAddress::INVALID_HOSTNAME   => "Not a valid email address.",
                    \Zend\Validator\EmailAddress::INVALID_MX_RECORD  => "Not a valid email address.",
                    \Zend\Validator\EmailAddress::INVALID_SEGMENT    => "Not a valid email address.",
                    \Zend\Validator\EmailAddress::DOT_ATOM           => "Not a valid email address.",
                    \Zend\Validator\EmailAddress::QUOTED_STRING      => "Not a valid email address.",
                    \Zend\Validator\EmailAddress::INVALID_LOCAL_PART => "Not a valid email address.",
                    \Zend\Validator\EmailAddress::LENGTH_EXCEEDED    => "Not a valid email address.",
                ],
            ],
        ];

        return $this;
    }

    /**
     * @param string $format
     * @param string $display
     *
     * @return $this
     */
    public function addDateValidator($format = 'm/d/Y', $display = 'mm/dd/yyyy')
    {
        $this->validators[] = [
            'name'                   => 'Date',
            'break_chain_on_failure' => true,
            'options'                => [
                'format'   => $format,
                'messages' => [
                    'dateInvalidDate' => 'Not a valid date.',
                    'dateFalseFormat' => "Must be in the format {$display}.",
                ],
            ],
        ];

        return $this;
    }

    public function addUrlValidator()
    {
        $this->validators[] = [
            'name'                   => 'Uri',
            'break_chain_on_failure' => true,
            'options'                => [
                'allowRelative' => false,
                'allowAbsolute' => true,
                'messages'      => [
                    'uriInvalid' => 'Not a valid url.',
                    'notUri'     => "Not a valid url.",
                ],
            ],
        ];

        return $this;
    }

    /**
     * @param int    $min
     * @param string $message
     *
     * @return $this
     */
    public function setMinLength(int $min, string $message = 'Must be at least %min% characters.')
    {
        $this->validators[] = [
            'name'                   => 'StringLength',
            'break_chain_on_failure' => true,
            'options'                => [
                'min'      => $min,
                'messages' => ['stringLengthTooShort' => $message],
            ],
        ];

        return $this;
    }

    /**
     * @param int    $max
     * @param string $message
     *
     * @return $this
     */
    public function setMaxLength(int $max, string $message = 'Must be no longer than %max% characters.')
    {
        $this->validators[] = [
            'name'                   => 'StringLength',
            'break_chain_on_failure' => true,
            'options'                => [
                'max'      => $max,
                'messages' => ['stringLengthTooLong' => $message],
            ],
        ];

        return $this;
    }

    /**
     * @param int    $max
     * @param bool   $inclusive
     * @param string $message
     *
     * @return $this
     */
    public function setMaxValue(int $max, $inclusive = false, string $message = 'Must be no more than %max%.')
    {
        $this->validators[] = [
            'name'                   => 'LessThan',
            'break_chain_on_failure' => true,
            'options'                => [
                'max'       => $max,
                'inclusive' => $inclusive,
                'messages'  => [
                    'notLessThan'          => $message,
                    'notLessThanInclusive' => $message,
                ],
            ],
        ];

        return $this;
    }

    /**
     * @param int    $min
     * @param bool   $inclusive
     * @param string $message
     *
     * @return $this
     */
    public function setMinValue(int $min, $inclusive = false, string $message = 'Must be at least %max%.')
    {
        $this->validators[] = [
            'name'                   => 'GreaterThan',
            'break_chain_on_failure' => true,
            'options'                => [
                'min'       => $min,
                'inclusive' => $inclusive,
                'messages'  => [
                    'notGreaterThan'          => $message,
                    'notGreaterThanInclusive' => $message,
                ],
            ],
        ];

        return $this;
    }

    /**
     * @param array $values
     * @param       $message
     *
     * @return $this
     */
    public function addInArrayValidator(array $values, $message = 'Value not found')
    {
        $this->validators[] = [
            'name'                   => 'InArray',
            'break_chain_on_failure' => true,
            'options'                => [
                'strict'   => InArray::COMPARE_NOT_STRICT_AND_PREVENT_STR_TO_INT_VULNERABILITY,
                'haystack' => $values,
                'messages' => ['notInArray' => $message],
            ],
        ];

        return $this;
    }

    /**
     * @param string $pattern
     * @param string $message
     *
     * @return $this
     */
    public function setRegex(string $pattern, string $message)
    {
        $this->validators[] = [
            'name'                   => 'Regex',
            'break_chain_on_failure' => true,
            'options'                => [
                'pattern'  => $pattern,
                'messages' => ['regexNotMatch' => $message],
            ],
        ];

        return $this;
    }

    /**
     * @param string $message
     *
     * @return $this
     */
    public function addNumericValidator(string $message = 'Must be a number')
    {
        $this->validators[] = [
            'name'                   => 'Zend\I18n\Validator\IsFloat',
            'break_chain_on_failure' => true,
            'options'                => [
                'messages' => ['notFloat' => $message],
            ],
        ];

        return $this;
    }

    /**
     * @param string $message
     *
     * @return $this
     */
    public function addIntegerValidator(string $message = 'Must be a number')
    {
        $this->validators[] = [
            'name'                   => 'Regex',
            'break_chain_on_failure' => true,
            'options'                => [
                'pattern'  => '/^-?\d+$/',
                'messages' => ['regexNotMatch' => $message],
            ],
        ];

        return $this;
    }

    /**
     * @param string $extensions
     * @param string $message
     *
     * @return $this
     */
    public function setFileExtensions(string $extensions, string $message = '')
    {
        $this->validators[] = [
            'name'                   => 'Zend\Validator\File\Extension',
            'break_chain_on_failure' => true,
            'options'                => [
                'extension' => $extensions,
                'messages'  => [
                    'fileExtensionFalse'    => $message,
                    'fileExtensionNotFound' => $message,
                ],
            ],
        ];

        return $this;
    }

    public function setUpload($message = 'File upload is required.')
    {
        $this->validators[] = [
            'name'                   => 'Zend\Validator\File\UploadFile',
            'break_chain_on_failure' => true,
            'options'                => [
                'messages' => [
                    'fileUploadFileErrorIniSize'      => 'Uploaded file is too large.',
                    'fileUploadFileErrorFormSize'     => 'Uploaded file is too large.',
                    'fileUploadFileErrorPartial'      => 'Failed to upload file.',
                    'fileUploadFileErrorNoFile'       => $message,
                    'fileUploadFileErrorNoTmpDir'     => 'Failed to upload file.',
                    'fileUploadFileErrorCantWrite'    => 'Failed to upload file.',
                    'fileUploadFileErrorExtension'    => 'Failed to upload file.',
                    'fileUploadFileErrorAttack'       => 'Failed to upload file.',
                    'fileUploadFileErrorFileNotFound' => 'Failed to upload file.',
                    'fileUploadFileErrorUnknown'      => 'Failed to upload file.',
                ],
            ],
        ];

        return $this;
    }

    /**
     * @return FormElement
     */
    public function addImageFileValidator()
    {
        return $this->setFileExtensions('jpg,png,gif,jpeg', 'Uploaded file must be an image.');
    }

    /**
     * @param string $field
     * @param string $message
     *
     * @return $this
     */
    public function addConfirmationValidator(string $field, string $message)
    {
        $this->validators[] = [
            'name'                   => 'Identical',
            'break_chain_on_failure' => true,
            'options'                => [
                'token'    => $field,
                'messages' => ['notSame' => $message],
            ],
        ];

        return $this;
    }

    /**
     * @param array|ValidatorInterface $validator
     *
     * @return $this
     */
    public function addValidator($validator)
    {
        $this->validators[] = $validator;

        return $this;
    }

    /**
     * @param array $filters
     *
     * @return $this
     */
    public function setFilters(array $filters)
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $element = [
            'type'        => $this->class,
            'name'        => $this->name,
            'required'    => $this->required,
            'allow_empty' => !$this->required,
            'filters'     => !empty($this->filters) ? $this->filters : $this->getFilters(),
            'validators'  => $this->validators,
        ];
        if (in_array($this->type, ['array', 'int_array'])) {
            $element['type'] = 'Zend\InputFilter\ArrayInput';
        }

        if ($this->required) {
            array_unshift($element['validators'], [
                'name'                   => 'NotEmpty',
                'break_chain_on_failure' => true,
                'options'                => [
                    'messages' => ['isEmpty' => $this->message],
                ],
            ]);
        }

        return $element;
    }

    /**
     * @return array
     */
    private function getFilters()
    {
        switch ($this->type) {
            case 'int':
            case 'integer':
                return $this->getIntegerFilters();
                break;
            case 'looseint':
                return $this->getIntegerFilters(true);
                break;
            case 'int_array':
                return $this->getIntegerFilters();
                break;
            case 'number':
            case 'float':
                return $this->getNumericFilters();
                break;
            case 'loosefloat':
                return $this->getNumericFilters(false);
                break;
            case 'email':
                return $this->getEmailFilters();
                break;
            case 'slug':
                return $this->getEmailFilters();
                break;
            case 'plain':
                return $this->getPlainFilters();
                break;
            case 'paragraph':
                return $this->getParagraphFilters();
                break;
            case 'textarea':
            case 'html':
                return $this->getTextareaFilters();
                break;
            case 'boolean':
                return $this->getBooleanFilters();
                break;
            case 'checkbox':
                return $this->getBooleanFilters();
                break;
            case 'checkbox_array':
                return $this->getBooleanFilters();
                break;
            case 'file':
            case 'image':
            case 'video':
            case 'audio':
                return [];
                break;
            case 'text':
            default:
                return $this->getTextFilters();
                break;
        }
    }

    /**
     * @return array
     */
    private function getTextFilters()
    {
        return [
            ['name' => 'StripNewlines'],
            ['name' => 'StringTrim'],
            ['name' => 'Null', 'options' => ['type' => 'string']],
        ];
    }

    /**
     * @return array
     */
    private function getTextareaFilters()
    {
        return [
            ['name' => 'StringTrim'],
            ['name' => 'Null', 'options' => ['type' => 'string']],
        ];
    }

    /**
     * @return array
     */
    private function getPlainFilters()
    {
        return [
            ['name' => 'StripTags'],
            ['name' => 'StripNewlines'],
            ['name' => 'StringTrim'],
            ['name' => 'Null', 'options' => ['type' => 'string']],
        ];
    }

    /**
     * @return array
     */
    private function getParagraphFilters()
    {
        return [
            ['name' => 'StripTags'],
            ['name' => 'StringTrim'],
            ['name' => 'Null', 'options' => ['type' => 'string']],
        ];
    }

    /**
     * @return array
     */
    private function getEmailFilters()
    {
        return [
            ['name' => 'StringToLower'],
            ['name' => 'StripNewlines'],
            ['name' => 'StringTrim'],
            ['name' => 'Null', 'options' => ['type' => 'string']],
        ];
    }

    /**
     * @param bool $loose
     *
     * @return array
     */
    private function getIntegerFilters($loose = false)
    {
        return [
            ['name' => 'StripNewlines'],
            ['name' => 'StringTrim'],
            ['name' => 'Int'],
            ['name' => 'Null', 'options' => ['type' => $loose ? 'string' : 'all']],
        ];
    }

    /**
     * @param bool $loose
     *
     * @return array
     */
    private function getNumericFilters($loose = false)
    {
        return [
            ['name' => 'StripNewlines'],
            ['name' => 'StringTrim'],
            ['name' => 'Callback', 'options' => ['callback' => 'floatval']],
            ['name' => 'Null', 'options' => ['type' => $loose ? 'string' : 'all']],
        ];
    }

    /**
     * @return array
     */
    private function getBooleanFilters()
    {
        return [
            ['name' => 'StripNewlines'],
            ['name' => 'StringTrim'],
            ['name' => 'Boolean', 'options' => ['type' => 'all']],
        ];
    }
}
