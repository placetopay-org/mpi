<?php


namespace PlacetoPay\MPI\Messages;


class LookUpResponse extends MPIBaseMessage
{
    protected $enrolled;
    protected $redirectUrl;
    protected $identifier;
    protected $eci;

    public function __construct($data)
    {
        $this->enrolled = $data['enrolled'];
        $this->redirectUrl = $data['redirectUrl'];
        $this->identifier = $data['identifier'];
        $this->eci = $data['eci'];
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

    public static function loadFromResult($result)
    {
        parent::loadFromResult($result);

        $data = [
            'enrolled' => $result['enrolled'],
            'redirectUrl' => isset($result['redirect_url']) ? $result['redirect_url'] : null,
            'identifier' => isset($result['transaction_id']) ? $result['transaction_id'] : null,
            'eci' => isset($result['eci_flag']) ? $result['eci_flag'] : null,
        ];

        return new self($data);
    }

}