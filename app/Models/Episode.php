<?php

namespace RickAndMorty\Models;

class Episode
{
    public int $id;
    private string $name;
    private string $airDate;
    private string $episode;
    private string $url;

    public function __construct(int $id, string $name, string $airDate, string $episode, string $url)
    {
        $this->id = $id;
        $this->name = $name;
        $this->airDate = $airDate;
        $this->episode = $episode;
        $this->url = $url;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAirDate(): string
    {
        return $this->airDate;
    }

    public function getEpisode(): string
    {
        return $this->episode;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}