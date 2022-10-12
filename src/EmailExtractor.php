<?php

namespace Donchev\EmailExtractor;

use Donchev\EmailExtractor\Exception\EmailExtractorException;

class EmailExtractor
{
    private const REGEX = "/(?:[a-z0-9!#$%&'*+?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/i";

    private $filter;

    private $regex;

    /**
     * @var null|array
     */
    private $emails = null;

    /**
     * @param array|null $filter An array of words used for filtering matched emails
     * @param string|null $regex A valid regex that should match an email address
     */
    public function __construct(array $filter = null, string $regex = null)
    {
        $this->filter = $filter ?? [];
        $this->regex = $regex ?? self::REGEX;
    }

    /**
     * @param string $content
     * @return $this
     * @throws EmailExtractorException
     */
    public function extract(string $content): self
    {
        if (!is_null($this->emails)) {
            throw new EmailExtractorException('This method should be called first!');
        }

        preg_match_all($this->regex, $content, $emails);

        $this->emails = $emails[0] ?? [];

        return $this;
    }

    /**
     * @param array $contents
     * @return $this
     * @throws EmailExtractorException
     */
    public function extractAll(array $contents): self
    {
        if (!is_null($this->emails)) {
            throw new EmailExtractorException('This method should be called first!');
        }

        $this->emails = [];

        $emails = [];
        foreach ($contents as $content) {
            preg_match_all($this->regex, $content, $emails);
            $emails = $emails[0] ?? [];
            $this->emails = array_merge($this->emails, $emails);
        }

        return $this;
    }

    /**
     * @return $this
     * @throws EmailExtractorException
     */
    public function unique(): self
    {
        if (is_null($this->emails)) {
            throw new EmailExtractorException('You need to export emails first');
        }

        $this->emails = array_unique($this->emails);

        return $this;
    }

    /**
     * @return $this
     * @throws EmailExtractorException
     */
    public function lower(): self
    {
        $this->convertCase();

        return $this;
    }

    /**
     * @return $this
     * @throws EmailExtractorException
     */
    public function upper(): self
    {
        $this->convertCase(false);

        return $this;
    }

    /**
     * @param array|null $filter
     * @return $this
     * @throws EmailExtractorException
     */
    public function filterExclude(array $filter = null): self
    {
        $this->filter(true, $filter);

        return $this;
    }

    /**
     * @param array|null $filter
     * @return $this
     * @throws EmailExtractorException
     */
    public function filterInclude(array $filter = null): self
    {
        $this->filter(false, $filter);

        return $this;
    }

    /**
     * @return $this
     * @throws EmailExtractorException
     */
    public function sortAsc(): self
    {
        $this->sort();

        return $this;
    }

    /**
     * @return $this
     * @throws EmailExtractorException
     */
    public function sortDesc(): self
    {
        $this->sort(false);

        return $this;
    }

    /**
     * @return array
     * @throws EmailExtractorException
     */
    public function export(): array
    {
        if (is_null($this->emails)) {
            throw new EmailExtractorException('You need to export emails first');
        }

        return $this->emails;
    }

    /**
     * @param bool $lower
     * @return void
     * @throws EmailExtractorException
     */
    private function convertCase(bool $lower = true)
    {
        if (is_null($this->emails)) {
            throw new EmailExtractorException('You need to export emails first');
        }

        $case = $lower ? 'mb_strtolower' : 'mb_strtoupper';

        $this->emails = array_map($case, $this->emails);
    }

    /**
     * @return void
     * @throws EmailExtractorException
     */
    private function sort(bool $asc = true)
    {
        if (is_null($this->emails)) {
            throw new EmailExtractorException('You need to export emails first');
        }

        if ($asc) {
            sort($this->emails);
        } else {
            rsort($this->emails);
        }
    }

    /**
     * @param bool $exclude
     * @param array|null $filter
     * @return $this
     * @throws EmailExtractorException
     */
    private function filter(bool $exclude = true, array $filter = null)
    {
        if (is_null($this->emails)) {
            throw new EmailExtractorException('You need to call "export" or "exportAll" method first');
        }

        $filter = $filter ?? $this->filter;

        $this->emails = array_filter($this->emails, function ($email) use ($filter, $exclude) {
            foreach ($filter as $item) {
                if ($exclude) {
                    if (mb_strpos($email, $item) !== false) {
                        return false;
                    }
                } else {
                    if (mb_strpos($email, $item) !== false) {
                        return true;
                    }
                }
            }

            return $exclude;
        });

        return $this;
    }
}