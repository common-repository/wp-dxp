<?php
class Wp_Dxp_Base_Model {

	protected static $table = '';

	protected $data = [];

    protected $attributes = [];

	/**
	 * Constructor
	 * @param array $attributes [description]
	 */
	public function __construct($attributes = [])
	{
		foreach ($this->attributes as $attribute) {
			$this->$attribute = null;
		}

		if ($attributes) {
			$this->hydrateFromArray($attributes);
		}
	}

	/**
	 * Save Model
	 * @return null
	 */
    public function save()
    {
    	global $wpdb;

    	$table = self::getTableName();
    	$this->modified_at = date('Y-m-d H:i:s');

		// Filter obj data to what is needed for the database, removing cache data.
		$data = array_filter( $this->data, function( $key ) {
			return in_array( $key, $this->attributes, true );
		}, ARRAY_FILTER_USE_KEY );

		if ( $this->id ) {
    		$wpdb->update( $wpdb->prefix . $table, $data, [ 'id' => $this->id ] );
    	} else {
    		$data['created_at'] = $this->created_at = date( 'Y-m-d H:i:s' );
    		$wpdb->insert( $wpdb->prefix . $table, $data );
    		$this->id = $wpdb->insert_id;
    	}
		// Replace/update cache of item.
		wp_cache_replace( $this->id, $data, $table );
    }

    /**
     * Clone this model and save
     * @return Wp_Dxp_Base_Model
     */
    public function clone()
    {
        $this->id = NULL;
        $this->name = $this->name . _x( ' copy', 'post fix added to name on duplication', 'wp-dxp' );

        // Names should be unique.
		$name_check = $this->check_name( $this->name );
        if ( $name_check ) {
            $suffix = 2;
			do {
				$alt_name   = substr( $this->name, 0, 200 ) . "-$suffix";
				$name_check = $this->check_name( $alt_name );
				$suffix++;
			} while ( $name_check );
			$this->name = $alt_name;
        }

        $this->save();

        return $this;
    }

    /**
     * Populate model attributes from array of data
     * @param  array $attributes
     */
    protected function hydrateFromArray($attributes)
    {
    	foreach ($attributes as $key => $value) {
    		$this->$key = $value;
    	}
    }

	/**
	 * Find model via ID
	 * @param  integer $id
	 * @return Wp_Dxp_Base_Model|false
	 */
	public static function find($id)
	{
		global $wpdb;

		$table = self::getTableName();

		$attributes = wp_cache_get( $id, $table );

		if ( ! $attributes ) {
			$attributes = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}{$table} WHERE id = %d;", $id ), ARRAY_A );

			if ( ! $attributes ) {
				return false;
			}
			wp_cache_add( $id, $attributes, $table );
		}
		return new static( $attributes );
	}

    /**
     * Find model via ID
     * @param  string $id
     * @return array|false
     */
    public static function findAll($id)
    {
        global $wpdb;

		$table = self::getTableName();

	    $id = array_unique(array_filter(explode(',', $id)));

		if ( count($id) === 1 ) {
			return [self::find($id[0])];
		}

		$attributes = wp_cache_get_multiple($id, $table);
	    $missingId = array_keys(array_filter($attributes, function($i) {
		    return $i === false;
	    }));

		if ( count($missingId) > 0 ) {
			$placeholders = array_map(function($i) {
				return '%d';
			}, $missingId);

			$missingAttributes = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}{$table} WHERE id IN (". implode(',', $placeholders) .")", $missingId), ARRAY_A );

			if ( $missingAttributes ) {
				foreach ( $missingAttributes as $attribute ) {
					$attributes[$attribute['id']] = $attribute;
					wp_cache_add( $attribute['id'], $attribute, $table );
				}
			}
		}

	    $attributes = array_map(function($attribute) {
		    return new static( $attribute );
	    }, array_filter($attributes));

        return $attributes;
    }

    /**
     * Check uniqueness via name
     * @param  string  $name
     * @param  integer $id
     * @return string|null
     */
    public static function check_name( $name, $id = 0 )
    {
        global $wpdb;

        $table = self::getTableName();

        $check_sql = "SELECT `name` FROM {$wpdb->prefix}{$table} WHERE `name` = %s AND ID != %d  LIMIT 1;";
        return $wpdb->get_var( $wpdb->prepare( $check_sql, $name, $id ) );
    }

    /**
     * Get all models
     * @return array
     */
    public static function all()
    {
        global $wpdb;

        $table = self::getTableName();

        $sql = "SELECT * FROM {$wpdb->prefix}{$table} ORDER BY name ASC;";
        $rows = $wpdb->get_results($sql, ARRAY_A);

        $return = [];
        foreach ($rows as $row) {
            $return[] = new static($row);
        }

        return $return;
    }

    /**
     * Get all models
     * @return array
     */
    public static function inCategory($category_id)
    {
        global $wpdb;

        $table = self::getTableName();

        $sql = "SELECT * FROM {$wpdb->prefix}{$table} ORDER BY id ASC;";
        $sql = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}{$table} WHERE category_id = %d;", [$category_id]);
        $wpdb->query($sql);
        $rows = $wpdb->get_results($sql, ARRAY_A);

        $return = [];
        foreach ($rows as $row) {
            $return[] = new static($row);
        }

        return $return;
    }

    /**
     * Delete model for specified ID
     * @param  integer $id
     * @return boolean
     */
    public static function delete($id)
    {
        global $wpdb;

		$table = self::getTableName();

		// Remove cached entry.
		wp_cache_delete( $id, $table );

		return $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}{$table} WHERE id = %d;", $id ) );
    }

    /**
     * Get models as list with specified properties as key & value
     * @return array
     */
    public static function list($key = 'id', $value = 'name')
    {
        $rows = static::all();

        $list = [];
        foreach ($rows as $row) {
            $list[$row->$key] = $row->$value;
        }

        return $list;
    }

    /**
     * Magic Getter
     * @param  string $property
     * @return mixed
     */
    public function __get($property)
    {
		// Check for db based data via defined attributes, or custom data in non-persistent cache.
        if ( in_array( $property, $this->attributes, true ) || array_key_exists( $property, $this->data ) ) {
            return stripslashes_deep( $this->data[ $property ] );
        }

        // Allow for custom attribute getters
        $attrMethodName = 'get' . str_replace('_', '', ucwords($property, '_')) . 'Attribute';

        if ( method_exists( $this, $attrMethodName ) ) {
			// Store results of method as non-persistent cache on object.
			$this->data[ $property ] = stripslashes_deep( $this->$attrMethodName() );
			return $this->data[ $property] ;
        }

        return null;
    }

    /**
     * Magic setter
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value)
    {
        if (in_array($property, $this->attributes)) {
            $this->data[$property] = $value;
        }
    }

    /**
     * Return the table name for the class
     * @return string
     */
    public static function getTableName()
    {
    	return static::$table;
    }

}
