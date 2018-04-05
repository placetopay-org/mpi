# 3DS MPI Service SDK

## Installation

This SDK can be installed easily through composer
```
composer require placetopay/mpi
```

## Usage

Instantiate the object with the service

```
$mpi = new \PlacetoPay\MPI\MPIService([
    'url' => 'THE_MPI_URL',
    'apiKey' => 'THE_API_KEY_HERE'
]);
```

```
$response = $mpi->lookUp([
    'card' => [
        'number' => '5476328554652171',
        'expirationYear' => '20',
        'expirationMonth' => '12',
    ],
    'amount' => 12000,
    'currency' => 'COP',
    'redirectUrl' => 'https://dnetix.co/ping/3ds',
    // --- OPTIONAL ---
    'recurrence' => [
        'interval' => '1',
        'dueDate' => '2020-01-01',
    ],
]);
```
