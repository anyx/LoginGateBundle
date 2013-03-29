<?php

namespace Anyx\LoginGateBundle\Storage;

use Symfony\Component\HttpFoundation\Request;

interface StorageInterface
{
    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return integer
     */
    public function getCountAttempts(Request $request);
    
    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function incrementCountAttempts(Request $request);
    
    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function clearCountAttempts(Request $request);

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function getLastAttemptDate(Request $request);
}