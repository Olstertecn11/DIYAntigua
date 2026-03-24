import NextLink from "next/link";
import { Box, Button, Container, Heading, Text, VStack } from "@chakra-ui/react";

export default function AffiliatePromoSection() {
  return (
    <Box py={20} bg="blue.600" color="white">
      <Container maxW="5xl">
        <VStack gap={5} textAlign="center">
          <Heading>Programa de afiliados para anfitriones</Heading>
          <Text maxW="2xl">
            Comparte tu enlace, recomienda traslados y gana comisiones por cada reserva confirmada.
          </Text>

          <NextLink href="/afiliados">
            <Button bg="white" color="blue.600" rounded="full">
              Conocer más
            </Button>
          </NextLink>
        </VStack>
      </Container>
    </Box>
  );
}
