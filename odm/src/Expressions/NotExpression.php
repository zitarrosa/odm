<?php
namespace Flaubert\Persistence\Elastic\ODM\Expressions;

class NotExpression extends Expression
{
    /**
     * @var Expression
     */
    protected $subject;

    public function __construct(Expression $subject)
    {
        $this->subject = $subject;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $subjectArray = $this->subject->toArray();

        return ['not' => $subjectArray];
    }
}