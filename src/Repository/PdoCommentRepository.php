<?php

namespace BlueCode\Repository;

use BlueCode\Model\Comment;
use PDO;

class PdoCommentRepository
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getById($id)
    {
        $statement = $this->pdo->prepare(
            "SELECT *
            FROM comment
            WHERE id=:id
            LIMIT 1"
        );
        $statement->execute(array(
            'id' => $id,
        ));
        $row = $statement->fetch();
        return $row ? $this->rowToObject($row) : null;
    }

    public function getByName($name)
    {
        $statement = $this->pdo->prepare(
            "SELECT *
            FROM comment
            WHERE name=:name
            LIMIT 1"
        );
        $statement->execute(array(
            'name' => $name
        ));
        $row = $statement->fetch();

        return $row ? $this->rowToObject($row) : null;
    }

    public function getAll()
    {
        $statement = $this->pdo->prepare(
            "SELECT * FROM comment"
        );
        $statement->execute();
        $rows = $statement->fetchAll();
        $objects = array();
        foreach ($rows as $row) {
            $objects[] = $this->rowToObject($row);
        }

        return $objects;
    }

    public function add(comment $comment)
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO comment () VALUES ()'
        );
        $statement->execute();
        $comment->setId($this->pdo->lastInsertId());
        $this->update($comment);

        return true;
    }

    public function update(comment $comment)
    {
        $statement = $this->pdo->prepare(
            "UPDATE comment
             SET name=:name, email=:email, description=:description
             WHERE id=:id"
        );
        $statement->execute(
            [
                'id' => $comment->getId(),
                'name' => $comment->getName(),
                'email' => $comment->getEmail(),
                'description' => $comment->getDescription(),
            ]
        );

        return $comment;
    }

    public function delete(comment $comment)
    {
        $statement = $this->pdo->prepare(
            "DELETE FROM comment WHERE id=:id"
        );
        $statement->execute(
            [
                'id' => $comment->getId(),
            ]
        );
    }

    private function rowToObject($row)
    {
        if (!$row) {
            return null;
        }
        $obj = new comment();
        $obj->setId($row['id'])
            ->setName($row['name'])
            ->setEmail($row['email'])
            ->setDescription($row['description']);

        return $obj;
    }
}
