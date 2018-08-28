<?php
    namespace App\Controllers;

    class ReservationController extends \App\Core\Controller {
        public function show($id) {
            $reservationModel = new \App\Models\ReservationModel($this->getDatabaseConnection());
            $reservation = $reservationModel->getById($id);

            if (!$reservation) {
                header('Location: /');
                exit;
            }

            $this->set('reservation', $reservation);

            $confirmedReservation  = $this->getLastOfferPrice($id);
            if (!$confirmedReservation ) {
                $confirmedReservation  = $reservation->starting_price;
            }

            $this->set('confirmedReservation ', $confirmedReservation );

            $reservationViewModel = new \App\Models\AuctionViewModel($this->getDatabaseConnection());

            $ipAddress = filter_input(INPUT_SERVER, 'REMOTE_ADDR');
            $userAgent = filter_input(INPUT_SERVER, 'HTTP_USER_AGENT');

            $reservationViewModel->add(
                [
                    'reservation_id' => $id,
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent
                ]
            );
        }

        private function getLastOfferPrice($reservationId) {
            $offerModel = new \App\Models\OfferModel($this->getDatabaseConnection());
            $offers = $offerModel->getAllByAuctionId($reservationId);
            $lastPrice = 0;

            foreach ($offers as $offer) {
                if ($lastPrice < $offer->price) {
                    $lastPrice = $offer->price;
                }
            }

            return $lastPrice;
        }
    }
