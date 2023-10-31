<?php
declare(strict_types=1);

namespace RickAndMorty\Controllers;

use Carbon\Carbon;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\HttpClient;
use Twig\Environment;
use RickAndMorty\Models\Episode;

class EpisodeController
{
    private HttpClientInterface $client;
    private Environment $twig;
    private array $episodes = [];

    public function __construct(Environment $twig)
    {
        $this->client = HttpClient::create();
        $this->twig = $twig;

        if (empty($this->episodes)) {
            $this->fetchEpisodes();
        }
    }

    private function fetchEpisodes(): void
    {
        $response = $this->client->request('GET', 'https://rickandmortyapi.com/api/episode');
        $episodeData = json_decode($response->getContent());

        foreach ($episodeData->results as $episodeData) {
            $airDate = Carbon::parse($episodeData->air_date)->format('F j, Y');

            $episode = new Episode(
                $episodeData->id,
                $episodeData->name,
                $airDate,
                $episodeData->episode,
                $episodeData->url
            );

            $this->episodes[] = $episode;
        }
    }

    public function index(): string
    {
        $template = $this->twig->load('episode_list.twig');

        return $template->render(['episodes' => $this->episodes]);
    }

    public function displayEpisode($vars): ?string
    {

        $episode = $this->findEpisode($vars['id']);

        if ($episode !== null) {
            $template = $this->twig->load('episode.twig');
            return $template->render(['episode' => $episode]);
        }
        return null;
    }

    private function findEpisode($id): ?Episode
    {
        foreach ($this->episodes as $episode) {
            if ($episode->getId() === (int)$id) {
                return $episode;
            }
        }
        return null;
    }
}