# 3DS MPI Service SDK

## Installation

This SDK can be installed easily through composer
```
composer require placetopay/mpi
```

## Usage

Instantiate the object with the service, you can send 3ds version, default value is V1

```
$mpi = new \PlacetoPay\MPI\MPIService([
    'url' => 'THE_MPI_URL',
    'apiKey' => 'THE_API_KEY_HERE'
     // --- OPTIONAL ---
    '3dsVersion' => 'V2' 
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
    // Only 3dsVersion V2
    'threeDSAuthenticationInd' => '',
    'recurringFrequency' => '',
    'recurringExpiry' => '',
    'recurringFrequency' => '',
    'purchaseInstalData' => '',
]);
```

###Optional Parameters



| Field  | Description  | Format  | Require  |
|---|---|---|---|
| threeDSAuthenticationInd  | type of authentication request.  | String(02):  - 01: Paymen transaction - 02: Recurring transaction -03: Installment transaction   |  Optional, default is 01 |
| recurringFrequency  | the minimum number of days between authorizations.  | String (max 32) |  if threeDSAuthenticationInd is '02' o '03' |
| recurringExpiry  | Date after which no further authorizations will be made  | String(8) / Format YYYYMMDD   |  if threeDSAuthenticationInd is '02' o '03'   |
| recurringFrequency  | Minimum number of days between authorizations.  | String(max 32)  | if threeDSAuthenticationInd is '02' o '03'  |
| purchaseInstalData  | Maximum number of authorizations allowed for installment payments.  | String(max 8) major than 1 | if threeDSAuthenticationInd is  '03'  |

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
