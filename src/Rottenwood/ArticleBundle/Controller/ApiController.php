<?php

namespace Rottenwood\ArticleBundle\Controller;

use Rottenwood\ArticleBundle\Entity\Article;
use Rottenwood\ArticleBundle\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * API контроллер
 * @package Rottenwood\ArticleBundle\Controller
 */
class ApiController extends Controller {

    /**
     * API: Получение списка статей по автору
     * @param Request $request
     * @return JsonResponse
     */
    public function getArticlesAction(Request $request) {
        $articleArray = array();
        $author = $request->request->get('author');

        if ($author == 0) {
            $articles = $this->get('article')->getArticles();
        } else {
            $articles = $this->get('article')->getArticlesByAuthor($author);
        }

        foreach ($articles as $article) {
            /** @var Article $article */
            $articleArrayId = $article->getId();
            $articleArray[$articleArrayId]['title'] = $article->getTitle();
            $articleArray[$articleArrayId]['date'] = $article->getDate()->format('d.m.Y');
            $articleArray[$articleArrayId]['rating'] = $article->getRating();

            $articleAuthors = $article->getAuthors();
            foreach ($articleAuthors as $articleAuthor) {
                /** @var Author $articleAuthor */
                $articleArray[$articleArrayId]['authors'][] = $articleAuthor->getName();
            }
            $articleArray[$articleArrayId]['authors'] = implode(',<br>', $articleArray[$articleArrayId]['authors']);
        }

        return new JsonResponse($articleArray);
    }

    /**
     * API: Создание статьи
     * @param Request $request
     * @return JsonResponse
     */
    public function addArticleAction(Request $request) {
        $title = $request->request->get('title');
        $text = $request->request->get('text');
        $authors = $request->request->get('author');

        $article = $this->get('article')->createArticle($title, $text, $authors);

        return new JsonResponse($article);
    }

    /**
     * API: Редактирование статьи
     * @param Request $request
     * @return JsonResponse
     */
    public function editArticleAction(Request $request) {
        $article = $request->request->get('article');
        $title = $request->request->get('title');
        $text = $request->request->get('text');
        $authors = $request->request->get('author');

        $article = $this->get('article')->editArticle($article, $title, $text, $authors);

        return new JsonResponse($article);
    }

    /**
     * API: Удаление статьи
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteArticleAction(Request $request) {
        $articleId = $request->request->get('articleId');

        $article = $this->get('article')->deleteArticle($articleId);

        return new JsonResponse($article);
    }

    /**
     * API: Запрос одной статьи по ID
     * @param Request $request
     * @return JsonResponse
     */
    public function getOneArticleAction(Request $request) {
        $articleId = $request->request->get('articleId');

        $article = $this->get('article')->getArticleById($articleId);

        return new JsonResponse($article);
    }

    /**
     * API: Поиск статей
     * @param Request $request
     * @return JsonResponse
     */
    public function searchAction(Request $request) {
        $articleArray = array();
        $keyword = $request->request->get('keyword');

        $articles = $this->get('article')->searchArticles($keyword);

        foreach ($articles as $article) {
            /** @var Article $article */
            $articleArrayId = $article->getId();
            $articleArray[$articleArrayId]['title'] = $article->getTitle();
            $articleArray[$articleArrayId]['date'] = $article->getDate()->format('d.m.Y');
            $articleArray[$articleArrayId]['rating'] = $article->getRating();

            $articleAuthors = $article->getAuthors();
            foreach ($articleAuthors as $articleAuthor) {
                /** @var Author $articleAuthor */
                $articleArray[$articleArrayId]['authors'][] = $articleAuthor->getName();
            }
            $articleArray[$articleArrayId]['authors'] = implode(',<br>', $articleArray[$articleArrayId]['authors']);
        }

        return new JsonResponse($articleArray);
    }

    /**
     * API: Запрос всех авторов
     * @return JsonResponse
     */
    public function getAllAuthorsAction() {
        $authors = $this->get('article')->getAuthors();

        $authorsArray = array();
        foreach ($authors as $author) {
            $authorsArray[$author->getId()] = $author->getName();
        }

        return new JsonResponse($authorsArray);
    }
}
