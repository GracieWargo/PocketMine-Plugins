<?php

/* 
__PocketMine Plugin__
name=Bounce
description=Magenta wool is now bouncy!
version=1.0
author=dsate1/GracieWargo
class=Bounce
apiversion=4
*/


class Bounce implements Plugin{
	private $api;
	public function __construct(ServerAPI $api, $server = false){
		$this->api = $api;
	}
	
	public function init(){
		$this->api->event("entity.move", array($this, "eventHandler"));
	}
	public function eventHandler($data, $event) {
		if ($data->fallY or $data->player == null) return;
		$x = (int) round($data->x - 0.5);
		$y = (int) round($data->y - 1);
		$z = (int) round($data->z - 0.5);
		if (isset($data->level)) {
			$block = $data->level->getBlock(new Vector3($x, $y, $z));
		} else {
			$block = $this->api->block->getBlock($x, $y, $z);
		}
		$is_bouncy_block = ($block->getID() == 35 and $block->getMetadata() == 2); //magenta wool
		if ($is_bouncy_block) {
			$data->speedY = -10;
			$data->player->dataPacket(MC_SET_ENTITY_MOTION, array(
				"eid" => 0,
				"speedX" => 0,
				"speedY" => (int) ($data->speedY * 32000),
				"speedZ" => 0,
			));
		}
		return true;
	}
	
	public function __destruct(){
	}
	
}
