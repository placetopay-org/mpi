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

## Lookup the card on the directories
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

## Query validation status
```
$response = $mpi->query(12345678);
```

## Update transaction status
```
$response = $mpi->update(12345678, new \PlacetoPay\MPI\Messages\UpdateTransactionRequest([
    'provider' => 'PlacetoPay',
    'processor' => 'CREDIBANCO',
    'authorization' => '909823',
    'iso' => '00'
]));
```
