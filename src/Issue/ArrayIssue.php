<?php


namespace Rs\Issues\Issue;

/**
 * GenericIssue
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class ArrayIssue
{
    protected $paths = [
        'url'          => [],
        'title'        => [],
        'desc'         => [],
        'created_at'   => [],
        'updated_at'   => [],
        'closed_at'    => [],
        'state'        => [],
        'comments'     => [],
        'assignee'     => [],
        'assignee_url' => [],
        'author'       => [],
        'author_url'   => [],
        'id'           => [],
        'type'         => [],
        'tags'         => [],
    ];

    protected $raw = [];

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
    public function getTitle()
    {
        return $this->attr('title');
    }

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
    public function getCreatedAt()
    {
        return new \DateTime($this->attr('created_at'));
    }

    /**
     * @inheritdoc
     */
    public function getUpdatedAt()
    {
        return $this->attr('updated_at') ? new \DateTime($this->attr('updated_at')) : null;
    }

    /**
     * @inheritdoc
     */
    public function getClosedAt()
    {
        return $this->attr('closed_at') ? new \DateTime($this->attr('closed_at')) : null;
    }

    /**
     * @inheritdoc
     */
    public function getState()
    {
        return $this->attr('state');
    }

    /**
     * @inheritdoc
     */
    public function getCommentCount()
    {
        return $this->attr('comments');
    }

    /**
     * @inheritdoc
     */
    public function getAssignee()
    {
        return $this->attr('assignee');
    }

    /**
     * @inheritdoc
     */
    public function getAssigneeUrl()
    {
        if ($this->getAssignee()) {
            return $this->attr('assignee_url');
        }
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->attr('id');
    }

    /**
     * @inheritdoc
     */
    public function getAuthor()
    {
        return $this->attr('author');
    }

    /**
     * @inheritdoc
     */
    public function getAuthorUrl()
    {
        return $this->attr('author_url');
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return $this->attr('type');
    }

    /**
     * @inheritdoc
     */
    public function getTags()
    {
        return $this->attr('tags', []);
    }

    /**
     * @param string $name
     * @param mixed $default
     *
     * @return mixed
     */
    protected function attr($name, $default = null)
    {
        if (!array_key_exists($name, $this->paths)) {
            throw new \InvalidArgumentException(sprintf('unknown attribute requested "%s"', $name));
        }

        return \igorw\get_in($this->raw, $this->paths[$name], $default);
    }
}
