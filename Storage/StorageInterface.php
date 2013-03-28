<?php

namespace Anyx\LoginGateBundle\Storage;

use Symfony\Component\HttpFoundation\Request;

interface StorageInterface
{
    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
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
}