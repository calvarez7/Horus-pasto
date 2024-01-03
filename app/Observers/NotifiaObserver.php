<?php

namespace App\Observers;

use App\Modelos\Megustanoticia;

class NotifiaObserver
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    /**
     * Handle the megustanoticia "created" event.
     *
     * @param  \App\Megustanoticia  $megustanoticia
     * @return void
     */

    public function index(Megustanoticia $megustanoticia)
    {
        //
    }

    public function created(Megustanoticia $megustanoticia)
    {
        //
    }

    /**
     * Handle the megustanoticia "updated" event.
     *
     * @param  \App\Megustanoticia  $megustanoticia
     * @return void
     */
    public function updated(Megustanoticia $megustanoticia)
    {
        //
    }

    /**
     * Handle the megustanoticia "deleted" event.
     *
     * @param  \App\Megustanoticia  $megustanoticia
     * @return void
     */
    public function deleted(Megustanoticia $megustanoticia)
    {
        //
    }

    /**
     * Handle the megustanoticia "restored" event.
     *
     * @param  \App\Megustanoticia  $megustanoticia
     * @return void
     */
    public function restored(Megustanoticia $megustanoticia)
    {
        //
    }

    /**
     * Handle the megustanoticia "force deleted" event.
     *
     * @param  \App\Megustanoticia  $megustanoticia
     * @return void
     */
    public function forceDeleted(Megustanoticia $megustanoticia)
    {
        //
    }
}
