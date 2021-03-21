<?php

namespace App\Core\Helpers\Composer\Schema\Schema;

class SupportSchema
{

    /**
     * @var string
     */
    private string $email;

    /**
     * @var string
     */
    private string $issues;

    /**
     * @var string
     */
    private string $forum;

    /**
     * @var string
     */
    private string $wiki;

    /**
     * @var string
     */
    private string $irc;

    /**
     * @var string
     */
    private string $source;

    /**
     * @var string
     */
    private string $docs;

    /**
     * @var string
     */
    private string $rss;

    /**
     * @var string
     */
    private string $chat;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getIssues(): string
    {
        return $this->issues;
    }

    /**
     * @param string $issues
     */
    public function setIssues(string $issues): void
    {
        $this->issues = $issues;
    }

    /**
     * @return string
     */
    public function getForum(): string
    {
        return $this->forum;
    }

    /**
     * @param string $forum
     */
    public function setForum(string $forum): void
    {
        $this->forum = $forum;
    }

    /**
     * @return string
     */
    public function getWiki(): string
    {
        return $this->wiki;
    }

    /**
     * @param string $wiki
     */
    public function setWiki(string $wiki): void
    {
        $this->wiki = $wiki;
    }

    /**
     * @return string
     */
    public function getIrc(): string
    {
        return $this->irc;
    }

    /**
     * @param string $irc
     */
    public function setIrc(string $irc): void
    {
        $this->irc = $irc;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @param string $source
     */
    public function setSource(string $source): void
    {
        $this->source = $source;
    }

    /**
     * @return string
     */
    public function getDocs(): string
    {
        return $this->docs;
    }

    /**
     * @param string $docs
     */
    public function setDocs(string $docs): void
    {
        $this->docs = $docs;
    }

    /**
     * @return string
     */
    public function getRss(): string
    {
        return $this->rss;
    }

    /**
     * @param string $rss
     */
    public function setRss(string $rss): void
    {
        $this->rss = $rss;
    }

    /**
     * @return string
     */
    public function getChat(): string
    {
        return $this->chat;
    }

    /**
     * @param string $chat
     */
    public function setChat(string $chat): void
    {
        $this->chat = $chat;
    }


}
