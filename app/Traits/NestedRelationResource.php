<?php

namespace App\Traits;

trait NestedRelationResource
{

	/**
     * Get directly parent nested relationship of data
     *
     * @param  string  $nestedRelations
     * @return array
     */
	public function whenLoadedNestedRelation(string $nestedRelations)
	{
		$relations = explode('.', $nestedRelations);

		$data = $this;

		foreach($relations as $relation){

			$data = $data->relationLoaded($relation) ? $data[$relation] : null;

			if(!$data){
				$data = null;
				break;
			}
		}

		return $this->when($data, $data);
	}
}
