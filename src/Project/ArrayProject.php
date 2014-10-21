<?php


namespace Rs\Issues\Project;

/**
 * GenericIssue
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class ArrayProject
{
    protected $paths = [
        'url'          => [],
        'name'         => [],
        'desc'         => [],
    ];

    protected $raw = [];

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return $this->attr('desc');
    }

    /**
     * @inheritdoc
     */
    public function getUrl()
    {
        return $this->attr('url');
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->attr('name');
    }


    protected function attr($name, $default = null)
    {
        if (!array_key_exists($name, $this->paths)) {
            throw new \InvalidArgumentException(sprintf('unknown attribute requested "%s"', $name));
        }

        return \igorw\get_in($this->raw, $this->paths[$name], $default);
    }
}
