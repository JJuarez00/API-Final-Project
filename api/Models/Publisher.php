<?php
/**
 * Author: Ethan Mull
 * Date: 9/24/2025
 * File: Publisher.php
 * Description:
 */

namespace VideogamesAPI\Models;

use Illuminate\Database\Eloquent\Model;

class Publisher extends Model{
    protected $table = 'publishers';
    protected $primaryKey = 'publisher_id';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = false;

    /** -------------------------------[ Break Point ]----------------------------------- **/

    // Defines One-to-Many Relationship
    public function videogames()
    {
        return $this->hasMany(Videogame::class, 'publisher_id');
    }

    /** -------------------------------[ Break Point ]----------------------------------- **/

    // Get ALL the Publishers.
    public static function getPublishers($request) {
        $publishers = self::all();
        $publishers = self::with('videogames')->get();
        return $publishers;
    }

    // Get Publisher by ID.
    public static function getPublisherById(int $publisherId) {
        $publisherId = self::findOrFail($publisherId);
        return $publisherId;
    }

    // [Relationship 1:M]
    // Returns all Video Games associated with a specific Publisher ID.
    public static function getPublisherVideogames(int $publisherId) {
        $publisherId = self::findOrFail($publisherId);
        $publisherId->load('videogames');
        return $publisherId;
    }

    /** -------------------------------[ Break Point ]----------------------------------- **/

    // Insert a new Publisher.
    public static function createPublisher($request) {
        // Retrieve parameters from request body.
        $params = $request->getParsedBody();

        // Create a new Publisher instance.
        $publisher = new Publisher();

        // Set the Publishers attributes.
        foreach($params as $field => $value) {
            $publisher->$field = $value;
        }

        // Insert the Publisher into the database.
        $publisher->save();

        return $publisher;
    }

    // Update a Publisher.
    public static function updatePublisher($request) {
        // Retrieve parameters from request body.
        $params = $request->getParsedBody();

        // Retrieve ID from request url.
        $id = $request->getAttribute('publisher_id');
        $publisher = self::findOrFail($id);

        if(!$publisher) {
            return false;
        }

        // Update attributes of the Publisher.
        foreach($params as $field => $value) {
            $publisher->$field = $value;
        }

        // Save the Publisher into the database
        $publisher->save();
        return $publisher;
    }

    // Delete a Publisher.
    public static function deletePublisher($request) {
        // Retrieve ID from the request url.
        $id = $request->getAttribute('publisher_id');
        $publisher = self::findOrFail($id);
        return ($publisher ? $publisher->delete() : $publisher);
    }

}