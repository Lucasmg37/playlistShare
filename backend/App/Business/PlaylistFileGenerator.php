<?php


namespace App\Business;

use App\Constants\PlaylistFileGenerator as ConstantsPlaylistFileGenerator;
use App\Model\Entity\PlaylistFileGenerator as EntityPlaylistFileGenerator;

class PlaylistFileGenerator
{

  public static function changeStatus($playlist_id, $status)
  {

    $playlistFileGenerator = new EntityPlaylistFileGenerator(["playlist_id" => $playlist_id]);
    $playlistFileGenerator->findAndMount();

    if (!$playlistFileGenerator->getId()) {
      $playlistFileGenerator->clearObject();
      $playlistFileGenerator->mount(["playlist_id" => $playlist_id, "status" => $status]);
      $playlistFileGenerator->insert();
      return $playlistFileGenerator;
    }

    if (
      $status === ConstantsPlaylistFileGenerator::GENERATING
      &&  $playlistFileGenerator->getStatus() === ConstantsPlaylistFileGenerator::GENERATING
    ) {
      return false;
    }

    $playlistFileGenerator->setStatus($status);
    $playlistFileGenerator->save();
    return $playlistFileGenerator;
  }
}
