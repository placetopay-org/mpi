<?php

namespace PlacetoPay\MPI\Messages;

class LookUpResponse extends MPIBaseMessage
{
    protected $enrolled;
    protected $redirectUrl;
    protected $identifier;
    protected $eci;
    /**
     * @var array
     */
    protected $formData;

    public function __construct($data)
    {
        $this->enrolled = $data['enrolled'];
        $this->redirectUrl = $data['redirectUrl'];
        $this->identifier = $data['identifier'];
        $this->eci = $data['eci'];
        if (isset($data['formData'])) {
            $this->formData = $data['formData'];
        }
    }

    /**
     * Return true if the user can be authenticated through the MPI
     * @return bool
     */
    public function canAuthenticate()
    {
        if ($this->redirectUrl) {
            return true;
        }
        return false;
    }

    /**
     * Returns the URL to send the user to finish the authentication process
     * @return string
     */
    public function processUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * Returns the request identifier used to query for the status of the authentication later on
     * @return string
     */
    public function identifier()
    {
        return $this->identifier;
    }

    public function enrolled()
    {
        return $this->enrolled;
    }

    public function eci()
    {
        return $this->eci;
    }

    public function formData()
    {
        return $this->formData;
    }

    public function toArray()
    {
        return [
            'enrolled' => $this->enrolled(),
            'redirectUrl' => $this->processUrl(),
            'identifier' => $this->identifier(),
            'eci' => $this->eci(),
            'formData' => $this->formData(),
        ];
    }

    public static function loadFromResult($result)
    {
        parent::loadFromResult($result);

        $data = [
            'enrolled' => $result['enrolled'],
            'redirectUrl' => isset($result['redirect_url']) ? $result['redirect_url'] : null,
            'identifier' => isset($result['transaction_id']) ? $result['transaction_id'] : null,
            'eci' => isset($result['eci_flag']) ? $result['eci_flag'] : null,
            'formData' => null,
        ];

        if (isset($result['acs_url'])) {
            $data['formData'] = [
                'acsUrl' => $result['acs_url'],
                'paReq' => $result['pa_req'],
                'termUrl' => $result['term_url'],
                'md' => isset($result['md']) ? $result['md'] : null,
            ];
        }

        return new self($data);
    }
}