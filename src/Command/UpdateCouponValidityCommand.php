<?php
    
    namespace App\Command;
    
    use App\Repository\CouponsRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    
    class UpdateCouponValidityCommand extends Command
    {
        protected static $defaultName = 'app:update-coupon-validity';
        
        private $entityManager;
        private $couponsRepository;
        
        public function __construct(EntityManagerInterface $entityManager, CouponsRepository $couponsRepository)
        {
            parent::__construct();
            $this->entityManager = $entityManager;
            $this->couponsRepository = $couponsRepository;
        }
        
        protected function configure(): void
        {
            $this
                ->setDescription('Updates the is_valid flag for coupons based on their validity date.');
        }
        
        protected function execute(InputInterface $input, OutputInterface $output): int
        {
            $today = new \DateTime();
            $coupons = $this->couponsRepository->findBy(['is_valid' => true]);
            
            foreach ($coupons as $coupon) {
                if ($coupon->getValidity() < $today) {
                    $coupon->setIsValid(false);
                }
            }
            
            $this->entityManager->flush();
            
            $output->writeln('Coupon validity updated.');
            
            return Command::SUCCESS;
        }
    }
