"use client";

import NextLink from "next/link";
import {
  Box,
  Button,
  Container,
  Flex,
  Image,
  HStack,
  Link,
  Text,
} from "@chakra-ui/react";

const navItems = [
  { label: "Inicio", href: "/" },
  { label: "Reservar", href: "/reservar" },
  { label: "Afiliados", href: "/afiliados" },
  { label: "Conductores", href: "/conductores" },
  { label: "Admin", href: "/admin" },
];

export default function AppNavbar() {
  return (
    <Box
      position="sticky"
      top="0"
      zIndex="1000"
      //bg="rgba(255,255,255,0.85)"
      bg="black"
      backdropFilter="blur(12px)"
    //borderBottom="1px solid"
    //borderColor="gray.200"
    >
      <Container maxW="7xl">
        <Flex h="72px" align="center" justify="space-between">
          <HStack>
            <Image src="/images/logo.png" w={50} />
            <Text fontSize="xl" fontWeight="bold" >
              DIYAntigua
            </Text>
          </HStack>

          <HStack gap={6} display={{ base: "none", md: "flex" }}>
            {navItems.map((item) => (
              <Link as={NextLink} key={item.href} href={item.href} fontWeight="medium">
                {item.label}
              </Link>
            ))}
          </HStack>

          <Button as={NextLink} href="/reservar" colorScheme="blue" rounded="full">
            Reservar ahora
          </Button>
        </Flex>
      </Container>
    </Box>
  );
}
