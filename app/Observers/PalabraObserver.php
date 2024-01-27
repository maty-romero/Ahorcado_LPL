<?php

namespace App\Observers;

use App\Models\Palabra;

class PalabraObserver
{
    /**
     * Handle the Palabra "created" event.
     */
    // validacion de la longitud de la palabra antes de que se cree el nuevo registro
    public function created(Palabra $palabra): void
    {
        $minLength = $palabra->dificultad->longitud_minima;
        $maxLength = $palabra->dificultad->longitud_maxima;

        if (strlen($palabra->palabra) < $minLength || strlen($palabra->palabra) > $maxLength) {
            
            echo "Error"; 
            //return false; 
            //throw new \Exception('La longitud de la palabra no cumple con los requisitos de la dificultad.');
        }
    }

    /**
     * Handle the Palabra "updated" event.
     */
    public function updated(Palabra $palabra): void
    {
        //
    }

    /**
     * Handle the Palabra "deleted" event.
     */
    public function deleted(Palabra $palabra): void
    {
        //
    }

    /**
     * Handle the Palabra "restored" event.
     */
    public function restored(Palabra $palabra): void
    {
        //
    }

    /**
     * Handle the Palabra "force deleted" event.
     */
    public function forceDeleted(Palabra $palabra): void
    {
        //
    }
}
